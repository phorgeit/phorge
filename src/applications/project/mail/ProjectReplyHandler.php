<?php

final class ProjectReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhorgeProject)) {
      throw new Exception(
        pht('Mail receiver is not a %s.', 'PhorgeProject'));
    }
  }

  public function getObjectPrefix() {
    return PhorgeProjectProjectPHIDType::TYPECONST;
  }

  protected function shouldCreateCommentFromMailBody() {
    return false;
  }

}
