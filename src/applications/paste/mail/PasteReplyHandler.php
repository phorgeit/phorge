<?php

final class PasteReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgePaste)) {
      throw new Exception(
        pht('Mail receiver is not a %s.', 'PhorgePaste'));
    }
  }

  public function getObjectPrefix() {
    return 'P';
  }

}
