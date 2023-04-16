<?php

abstract class PhorgeProjectTriggerController
  extends PhorgeProjectController {

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();

    $crumbs->addTextCrumb(
      pht('Triggers'),
      $this->getApplicationURI('trigger/'));

    return $crumbs;
  }

}
