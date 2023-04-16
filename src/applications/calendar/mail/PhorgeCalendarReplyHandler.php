<?php

final class PhorgeCalendarReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeCalendarEvent)) {
      throw new Exception(
        pht(
          'Mail receiver is not a %s!',
          'PhorgeCalendarEvent'));
    }
  }

  public function getObjectPrefix() {
    return 'E';
  }
}
