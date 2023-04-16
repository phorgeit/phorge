<?php

final class PhorgeCountdownReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeCountdown)) {
      throw new Exception(pht('Mail receiver is not a %s!', 'Countdown'));
    }
  }

  public function getObjectPrefix() {
    return 'C';
  }

}
