<?php

final class PonderAnswerMailReceiver extends PhabricatorObjectMailReceiver {

  public function isEnabled() {
    return PhabricatorApplication::isClassInstalled(
      PhabricatorPonderApplication::class);
  }

  protected function getObjectPattern() {
    return 'ANSR[1-9]\d*';
  }

  protected function loadObject($pattern, PhabricatorUser $viewer) {
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
