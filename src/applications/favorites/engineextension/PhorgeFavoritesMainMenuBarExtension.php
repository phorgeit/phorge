<?php

final class PhorgeFavoritesMainMenuBarExtension
  extends PhorgeMainMenuBarExtension {

  const MAINMENUBARKEY = 'favorites';

  public function isExtensionEnabledForViewer(PhorgeUser $viewer) {
    return PhorgeApplication::isClassInstalledForViewer(
      'PhorgeFavoritesApplication',
      $viewer);
  }

  public function getExtensionOrder() {
    return 1100;
  }

  public function buildMainMenus() {
    $viewer = $this->getViewer();

    $dropdown = $this->newDropdown($viewer);
    if (!$dropdown) {
      return array();
    }

    $favorites_menu = id(new PHUIButtonView())
      ->setTag('a')
      ->setHref('#')
      ->setIcon('fa-bookmark')
      ->addClass('phorge-core-user-menu')
      ->setNoCSS(true)
      ->setDropdown(true)
      ->setDropdownMenu($dropdown)
      ->setAuralLabel(pht('Favorites Menu'));

    return array(
      $favorites_menu,
    );
  }

  private function newDropdown(PhorgeUser $viewer) {
    $applications = id(new PhorgeApplicationQuery())
      ->setViewer($viewer)
      ->withClasses(array('PhorgeFavoritesApplication'))
      ->withInstalled(true)
      ->execute();
    $favorites = head($applications);
    if (!$favorites) {
      return null;
    }

    $menu_engine = id(new PhorgeFavoritesProfileMenuEngine())
      ->setViewer($viewer)
      ->setProfileObject($favorites)
      ->setCustomPHID($viewer->getPHID());

    $controller = $this->getController();
    if ($controller) {
      $menu_engine->setController($controller);
    }

    $filter_view = $menu_engine->newProfileMenuItemViewList()
      ->newNavigationView();

    $menu_view = $filter_view->getMenu();
    $item_views = $menu_view->getItems();

    $view = id(new PhorgeActionListView())
      ->setViewer($viewer);
    foreach ($item_views as $item) {
      $action = id(new PhorgeActionView())
        ->setName($item->getName())
        ->setHref($item->getHref())
        ->setIcon($item->getIcon())
        ->setType($item->getType());
      $view->addAction($action);
    }

    return $view;
  }

}
