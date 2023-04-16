<?php

final class PhorgeMacroReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeFileImageMacro)) {
      throw new Exception(
        pht('Mail receiver is not a %s!', 'PhorgeFileImageMacro'));
    }
  }

  public function getObjectPrefix() {
    return 'MCRO';
  }

}
