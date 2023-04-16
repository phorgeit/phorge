<?php

abstract class PhorgeDashboardPortalController
  extends PhorgeDashboardController {

  protected function buildApplicationCrumbs() {
    $crumbs = new PHUICrumbsView();

    $crumbs->addCrumb(
      id(new PHUICrumbView())
        ->setHref('/portal/')
        ->setName(pht('Portals'))
        ->setIcon('fa-compass'));

    return $crumbs;
  }

}
