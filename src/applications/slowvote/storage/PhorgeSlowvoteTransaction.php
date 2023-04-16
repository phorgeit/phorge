<?php

final class PhorgeSlowvoteTransaction
  extends PhorgeModularTransaction {

  const MAILTAG_DETAILS = 'vote:details';
  const MAILTAG_RESPONSES = 'vote:responses';
  const MAILTAG_OTHER  = 'vote:vote';

  public function getApplicationName() {
    return 'slowvote';
  }

  public function getApplicationTransactionType() {
    return PhorgeSlowvotePollPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeSlowvoteTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgeSlowvoteTransactionType';
  }

  public function getMailTags() {
    $tags = parent::getMailTags();

    switch ($this->getTransactionType()) {
      case PhorgeSlowvoteQuestionTransaction::TRANSACTIONTYPE:
      case PhorgeSlowvoteDescriptionTransaction::TRANSACTIONTYPE:
      case PhorgeSlowvoteShuffleTransaction::TRANSACTIONTYPE:
      case PhorgeSlowvoteStatusTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_DETAILS;
        break;
      case PhorgeSlowvoteResponsesTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_RESPONSES;
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }

    return $tags;
  }


}
