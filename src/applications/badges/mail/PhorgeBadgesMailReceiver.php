<?php

final class PhorgeBadgesMailReceiver
  extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeBadgesApplication');
  }

  protected function getObjectPattern() {
    return 'BDGE[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 4);

    return id(new PhorgeBadgesQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PhorgeBadgesReplyHandler();
  }

}
