<?php

final class PhorgeAuditReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeRepositoryCommit)) {
      throw new Exception(
        pht(
          'Mail receiver is not a %s!',
          'PhorgeRepositoryCommit'));
    }
  }

  public function getObjectPrefix() {
    return 'COMMIT';
  }

}
