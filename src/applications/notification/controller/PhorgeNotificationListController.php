<?php

final class PhorgeNotificationListController
  extends PhorgeNotificationController {

  public function handleRequest(AphrontRequest $request) {
    $querykey = $request->getURIData('queryKey');

    $controller = id(new PhorgeApplicationSearchController())
      ->setQueryKey($querykey)
      ->setSearchEngine(new PhorgeNotificationSearchEngine())
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }

  public function buildSideNavView() {
    $viewer = $this->getViewer();

    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));

    id(new PhorgeNotificationSearchEngine())
      ->setViewer($viewer)
      ->addNavigationItems($nav->getMenu());
    $nav->selectFilter(null);

    return $nav;
  }

}
