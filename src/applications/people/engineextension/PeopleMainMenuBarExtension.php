<?php

final class PeopleMainMenuBarExtension
  extends PhorgeMainMenuBarExtension {

  const MAINMENUBARKEY = 'user';

  public function isExtensionEnabledForViewer(PhorgeUser $viewer) {
    return $viewer->isLoggedIn();
  }

  public function shouldRequireFullSession() {
    return false;
  }

  public function getExtensionOrder() {
    return 1200;
  }

  public function buildMainMenus() {
    $viewer = $this->getViewer();
    $application = $this->getApplication();
    $dropdown_menu = $this->newDropdown($viewer, $application);

    $menu_id = celerity_generate_unique_node_id();

    Javelin::initBehavior(
      'user-menu',
      array(
        'menuID' => $menu_id,
        'menu' => $dropdown_menu->getDropdownMenuMetadata(),
      ));

    $image = $viewer->getProfileImageURI();
    $profile_image = id(new PHUIIconView())
      ->setImage($image)
      ->setHeadSize(PHUIIconView::HEAD_SMALL);

    $user_menu = id(new PHUIButtonView())
      ->setID($menu_id)
      ->setTag('a')
      ->setHref('/p/'.$viewer->getUsername().'/')
      ->setIcon($profile_image)
      ->addClass('phorge-core-user-menu')
      ->setHasCaret(true)
      ->setNoCSS(true)
      ->setAuralLabel(pht('Account Menu'));

    return array(
      $user_menu,
    );
  }

  private function newDropdown(
    PhorgeUser $viewer,
    $application) {

    $person_to_show = id(new PHUIObjectItemView())
      ->setObjectName($viewer->getRealName())
      ->setSubHead($viewer->getUsername())
      ->setImageURI($viewer->getProfileImageURI());

    $user_view = id(new PHUIObjectItemListView())
      ->setViewer($viewer)
      ->setFlush(true)
      ->setSimple(true)
      ->addItem($person_to_show)
      ->addClass('phorge-core-user-profile-object');

    $view = id(new PhorgeActionListView())
      ->setViewer($viewer);

    if ($this->getIsFullSession()) {
      $view->addAction(
        id(new PhorgeActionView())
          ->appendChild($user_view));

      $view->addAction(
        id(new PhorgeActionView())
          ->setType(PhorgeActionView::TYPE_DIVIDER));

      $view->addAction(
        id(new PhorgeActionView())
          ->setName(pht('Profile'))
          ->setHref('/p/'.$viewer->getUsername().'/'));

      $view->addAction(
        id(new PhorgeActionView())
          ->setName(pht('Settings'))
          ->setHref('/settings/user/'.$viewer->getUsername().'/'));

      $view->addAction(
        id(new PhorgeActionView())
          ->setName(pht('Manage'))
          ->setHref('/people/manage/'.$viewer->getID().'/'));

      if ($application) {
        $help_links = $application->getHelpMenuItems($viewer);
        if ($help_links) {
          foreach ($help_links as $link) {
            $view->addAction($link);
          }
        }
      }

      $view->addAction(
        id(new PhorgeActionView())
          ->addSigil('logout-item')
          ->setType(PhorgeActionView::TYPE_DIVIDER));
    }

    $view->addAction(
      id(new PhorgeActionView())
        ->setName(pht('Log Out %s', $viewer->getUsername()))
        ->addSigil('logout-item')
        ->setHref('/logout/')
        ->setWorkflow(true));

    return $view;
  }

}
