<?php

final class LegalpadMailReceiver extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeLegalpadApplication');
  }

  protected function getObjectPattern() {
    return 'L[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new LegalpadDocumentQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needDocumentBodies(true)
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new LegalpadReplyHandler();
  }

}
