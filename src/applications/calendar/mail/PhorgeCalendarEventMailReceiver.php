<?php

final class PhorgeCalendarEventMailReceiver
  extends PhorgeObjectMailReceiver {

  public function isEnabled() {
    $app_class = 'PhorgeCalendarApplication';
    return PhorgeApplication::isClassInstalled($app_class);
  }

  protected function getObjectPattern() {
    return 'E[1-9]\d*';
  }

  protected function loadObject($pattern, PhorgeUser $viewer) {
    $id = (int)substr($pattern, 1);

    return id(new PhorgeCalendarEventQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PhorgeCalendarReplyHandler();
  }

}
