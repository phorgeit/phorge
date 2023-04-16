<?php

final class ConpherenceThreadMailReceiver
  extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    $app_class = 'PhorgeConpherenceApplication';
    return PhorgeApplication::isClassInstalled($app_class);
  }

  protected function getObjectPattern() {
    return 'Z[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new ConpherenceThreadQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new ConpherenceReplyHandler();
  }

}
