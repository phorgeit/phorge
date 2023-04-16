<?php

final class PonderAnswerMailReceiver extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    $app_class = 'PhorgePonderApplication';
    return PhorgeApplication::isClassInstalled($app_class);
  }

  protected function getObjectPattern() {
    return 'ANSR[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 4);

    return id(new PonderAnswerQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PonderAnswerReplyHandler();
  }

}
