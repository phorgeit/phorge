<?php

final class PhorgePolicyNamedPolicyListController
  extends PhabricatorNamedPolicyController {

 public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();

    $query_key = $request->getURIData('queryKey');
    $controller = id(new PhabricatorApplicationSearchController())
      ->setQueryKey($query_key)
      ->setSearchEngine(new PhorgeNamedPolicySearchEngine())
      ->setNavigation($this->buildSideNavView());

    return $this->delegateToController($controller);
  }


  public function buildSideNavView($filter = null) {
    $user = $this->getRequest()->getUser();

    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));

    id(new PhorgeNamedPolicySearchEngine())
      ->setViewer($user)
      ->addNavigationItems($nav->getMenu());

    $nav->selectFilter($filter);

    return $nav;
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    id(new PhorgeNamedPolicyEditEngine())
      ->setViewer($this->getViewer())
      ->addActionToCrumbs($crumbs);

    return $crumbs;
  }

}
