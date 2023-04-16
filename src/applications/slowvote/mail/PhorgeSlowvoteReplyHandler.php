<?php

final class PhorgeSlowvoteReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeSlowvotePoll)) {
      throw new Exception(pht('Mail receiver is not a %s!', 'Slowvote'));
    }
  }

  public function getObjectPrefix() {
    return 'V';
  }

}
