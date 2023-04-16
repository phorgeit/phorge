<?php

final class PhorgeAuthSSHKeyReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeAuthSSHKey)) {
      throw new Exception(
        pht('Mail receiver is not a %s!', 'PhorgeAuthSSHKey'));
    }
  }

  public function getObjectPrefix() {
    return 'SSHKEY';
  }

}
