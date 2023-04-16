<?php

final class PhorgeMacroListController extends PhorgeMacroController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $key = $request->getURIData('key');

    $controller = id(new PhorgeApplicationSearchController())
      ->setQueryKey($key)
      ->setSearchEngine(new PhorgeMacroSearchEngine())
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }

}
