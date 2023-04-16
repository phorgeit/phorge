<?php

final class PasteMailReceiver extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    $app_class = 'PhorgePasteApplication';
    return PhorgeApplication::isClassInstalled($app_class);
  }

  protected function getObjectPattern() {
    return 'P[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new PhorgePasteQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PasteReplyHandler();
  }

}
