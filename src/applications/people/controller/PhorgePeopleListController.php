<?php

final class PhorgePeopleListController
  extends PhorgePeopleController {

  public function shouldAllowPublic() {
    return true;
  }

  public function shouldRequireAdmin() {
    return false;
  }

  public function handleRequest(AphrontRequest $request) {
    $this->requireApplicationCapability(
      PeopleBrowseUserDirectoryCapability::CAPABILITY);

    $controller = id(new PhorgeApplicationSearchController())
      ->setQueryKey($request->getURIData('queryKey'))
      ->setSearchEngine(new PhorgePeopleSearchEngine())
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();
    $viewer = $this->getRequest()->getUser();

    if ($viewer->getIsAdmin()) {
      $crumbs->addAction(
        id(new PHUIListItemView())
        ->setName(pht('Create New User'))
        ->setHref($this->getApplicationURI('create/'))
        ->setIcon('fa-plus-square'));
    }

    return $crumbs;
  }


}
