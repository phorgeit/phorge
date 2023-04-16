<?php

abstract class PhorgePeopleInviteController
  extends PhorgePeopleController {

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();
    $crumbs->addTextCrumb(
      pht('Invites'),
      $this->getApplicationURI('invite/'));
    return $crumbs;
  }

}
