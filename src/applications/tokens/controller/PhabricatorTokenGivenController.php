<?php

final class PhabricatorTokenGivenController extends PhabricatorTokenController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $querykey = $request->getURIData('queryKey');

    $controller = id(new PhabricatorApplicationSearchController())
      ->setQueryKey($querykey)
      ->setSearchEngine(new PhabricatorTokenGivenSearchEngine())
      ->setNavigation($this->buildSideNav());

    return $this->delegateToController($controller);
  }

  protected function buildSideNav() {
    $nav = parent::buildSideNav();
    $viewer = $this->getViewer();

    id(new PhabricatorTokenGivenSearchEngine())
      ->setViewer($viewer)
      ->addNavigationItems($nav->getMenu());

    return $nav;
  }

}
