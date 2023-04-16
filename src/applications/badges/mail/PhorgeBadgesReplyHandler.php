<?php

final class PhorgeBadgesReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeBadgesBadge)) {
      throw new Exception(pht('Mail receiver is not a %s!', 'Badges'));
    }
  }

  public function getObjectPrefix() {
    return 'BDGE';
  }

}
