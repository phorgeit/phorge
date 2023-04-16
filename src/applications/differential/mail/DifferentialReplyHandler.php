<?php

final class DifferentialReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof DifferentialRevision)) {
      throw new Exception(pht('Receiver is not a %s!', 'DifferentialRevision'));
    }
  }

  public function getObjectPrefix() {
    return 'D';
  }

}
