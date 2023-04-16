<?php

final class ManiphestTaskListController
  extends ManiphestController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $querykey = $request->getURIData('queryKey');

    $controller = id(new PhorgeApplicationSearchController())
      ->setQueryKey($querykey)
      ->setSearchEngine(
        id(new ManiphestTaskSearchEngine())
          ->setShowBatchControls(true))
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }

}
