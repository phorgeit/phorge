<?php

final class PhorgeMacroMailReceiver extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    return PhorgeApplication::isClassInstalled(
      'PhorgeManiphestApplication');
  }

  protected function getObjectPattern() {
    return 'MCRO[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 4);

    return id(new PhorgeMacroQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PhorgeMacroReplyHandler();
  }

}
