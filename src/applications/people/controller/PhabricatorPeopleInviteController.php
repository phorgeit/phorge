<?php

abstract class PhabricatorPeopleInviteController
  extends PhabricatorPeopleController {

  public function shouldRequireAdmin() {
    // The invite system supports non-admins very well.
    // Non-admins can only see their invitees.
    return false;
  }

  protected function buildApplicationCrumbs() {
    $crumbs = parent::buildApplicationCrumbs();
    $crumbs->addTextCrumb(
      pht('Invites'),
      $this->getApplicationURI('invite/'));
    return $crumbs;
  }

}
