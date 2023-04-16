<?php

final class PassphraseCredentialListController extends PassphraseController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $querykey = $request->getURIData('queryKey');

    $controller = id(new PhorgeApplicationSearchController())
      ->setQueryKey($querykey)
      ->setSearchEngine(new PassphraseCredentialSearchEngine())
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }

}
