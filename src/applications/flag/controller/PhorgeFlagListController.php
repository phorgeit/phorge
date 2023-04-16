<?php

final class PhorgeFlagListController extends PhorgeFlagController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $querykey = $request->getURIData('queryKey');

    $controller = id(new PhorgeApplicationSearchController())
      ->setQueryKey($querykey)
      ->setSearchEngine(new PhorgeFlagSearchEngine())
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }

}
