<?php

final class PhorgePolicyNamedPolicyReplyHandler
extends PhabricatorApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeNamedPolicy)) {
      throw new Exception(
        pht('Mail receiver is not a %s!', 'PhorgeNamedPolicy'));
    }
  }

  public function getObjectPrefix() {
    return PhorgePolicyPHIDTypeNamedPolicy::TYPECONST;
  }

}
