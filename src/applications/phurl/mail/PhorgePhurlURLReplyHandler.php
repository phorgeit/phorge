<?php

final class PhorgePhurlURLReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgePhurlURL)) {
      throw new Exception(
        pht(
          'Mail receiver is not a %s!',
          'PhorgePhurlURL'));
    }
  }

  public function getObjectPrefix() {
    return 'U';
  }

}
