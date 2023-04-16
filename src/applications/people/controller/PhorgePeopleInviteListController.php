<?php

final class PhorgePeopleInviteListController
  extends PhorgePeopleInviteController {

  public function handleRequest(AphrontRequest $request) {
    $controller = id(new PhorgeApplicationSearchController())
      ->setQueryKey($request->getURIData('queryKey'))
      ->setSearchEngine(new PhorgeAuthInviteSearchEngine())
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }

  public function buildSideNavView($for_app = false) {
    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));

    $viewer = $this->getRequest()->getUser();

    id(new PhorgeAuthInviteSearchEngine())
      ->setViewer($viewer)
      ->addNavigationItems($nav->getMenu());

    return $nav;
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    $can_invite = $this->hasApplicationCapability(
      PeopleCreateUsersCapability::CAPABILITY);
    $crumbs->addAction(
      id(new PHUIListItemView())
        ->setName(pht('Invite Users'))
        ->setHref($this->getApplicationURI('invite/send/'))
        ->setIcon('fa-plus-square')
        ->setDisabled(!$can_invite)
        ->setWorkflow(!$can_invite));

    return $crumbs;
  }

}
