<?php

final class LegalpadReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof LegalpadDocument)) {
      throw new Exception(pht('Mail receiver is not a LegalpadDocument!'));
    }
  }

  public function getObjectPrefix() {
    return 'L';
  }

}
