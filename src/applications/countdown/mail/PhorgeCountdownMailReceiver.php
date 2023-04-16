<?php

final class PhorgeCountdownMailReceiver
  extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeCountdownApplication');
  }

  protected function getObjectPattern() {
    return 'C[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new PhorgeCountdownQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PhorgeCountdownReplyHandler();
  }

}
