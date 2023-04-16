<?php

final class PhameBlogReplyHandler
  extends PhorgeApplicationTransactionReplyHandler {

  public function validateMailReceiver($mail_receiver) {
    if (!($mail_receiver instanceof PhameBlog)) {
      throw new Exception(
        pht('Mail receiver is not a %s.', 'PhameBlog'));
    }
  }

  public function getObjectPrefix() {
    return PhorgePhameBlogPHIDType::TYPECONST;
  }

  protected function shouldCreateCommentFromMailBody() {
    return false;
  }

}
