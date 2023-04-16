<?php

final class PonderQuestionMailReceiver extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    $app_class = 'PhorgePonderApplication';
    return PhorgeApplication::isClassInstalled($app_class);
  }

  protected function getObjectPattern() {
    return 'Q[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new PonderQuestionQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PonderQuestionReplyHandler();
  }

}
