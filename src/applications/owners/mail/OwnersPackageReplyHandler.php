<?php

final class OwnersPackageReplyHandler extends PhorgeMailReplyHandler {
  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeOwnersPackage)) {
      throw new Exception(
        pht(
          'Receiver is not a %s!',
          'PhorgeOwnersPackage'));
    }
  }

  public function getPrivateReplyHandlerEmailAddress(
    PhorgeUser $user) {
    return null;
  }

  public function getPublicReplyHandlerEmailAddress() {
    return null;
  }

  protected function receiveEmail(PhorgeMetaMTAReceivedMail $mail) {
    return;
  }
}
