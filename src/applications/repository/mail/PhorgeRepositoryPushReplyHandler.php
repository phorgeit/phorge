<?php

final class PhorgeRepositoryPushReplyHandler
  extends PhorgeMailReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    return;
  }

  public function getPrivateReplyHandlerEmailAddress(
    PhorgeUser $user) {
    return null;
  }

  protected function receiveEmail(PhorgeMetaMTAReceivedMail $mail) {
    return;
  }

}
