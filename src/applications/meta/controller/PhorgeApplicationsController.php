<?php

abstract class PhorgeApplicationsController extends PhorgeController {

  public function buildSideNavView($for_app = false) {
    $user = $this->getRequest()->getUser();

    $nav = new AphrontSideNavFilterView();
    $nav->setBaseURI(new PhutilURI($this->getApplicationURI()));

    id(new PhorgeAppSearchEngine())
      ->setViewer($user)
      ->addNavigationItems($nav->getMenu());

    $nav->selectFilter(null);

    return $nav;
  }

  public function buildApplicationMenu() {
    return $this->buildSideNavView(true)->getMenu();
  }

  protected function addApplicationCrumb(
    PHUICrumbsView $crumbs,
    PhorgeApplication $application) {

    $crumbs->addTextCrumb(
      $application->getName(),
      '/applications/view/'.get_class($application).'/');
  }

}
