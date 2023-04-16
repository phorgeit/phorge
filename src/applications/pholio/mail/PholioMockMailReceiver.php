<?php

final class PholioMockMailReceiver extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    $app_class = 'PhorgePholioApplication';
    return PhorgeApplication::isClassInstalled($app_class);
  }

  protected function getObjectPattern() {
    return 'M[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new PholioMockQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PholioReplyHandler();
  }

}
