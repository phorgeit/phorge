<?php

final class FileReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeFile)) {
      throw new Exception(pht('Mail receiver is not a %s.', 'PhorgeFile'));
    }
  }

  public function getObjectPrefix() {
    return 'F';
  }

}
