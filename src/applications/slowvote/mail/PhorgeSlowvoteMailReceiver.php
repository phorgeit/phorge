<?php

final class PhorgeSlowvoteMailReceiver
  extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeSlowvoteApplication');
  }

  protected function getObjectPattern() {
    return 'V[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new PhorgeSlowvoteQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PhorgeSlowvoteReplyHandler();
  }

}
