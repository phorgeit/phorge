<?php

final class PhorgeAuditMailReceiver extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeDiffusionApplication');
  }

  protected function getObjectPattern() {
    return 'COMMIT[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)preg_replace('/^COMMIT/i', '', $pattern);

    return id(new DiffusionCommitQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needAuditRequests(true)
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PhorgeAuditReplyHandler();
  }

}
