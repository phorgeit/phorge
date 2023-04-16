<?php

final class PhorgeCountdownTransaction
  extends PhorgeModularTransaction {

  const MAILTAG_DETAILS = 'countdown:details';
  const MAILTAG_COMMENT = 'countdown:comment';
  const MAILTAG_OTHER  = 'countdown:other';

  public function getApplicationName() {
    return 'countdown';
  }

  public function getApplicationTransactionType() {
    return PhorgeCountdownCountdownPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeCountdownTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgeCountdownTransactionType';
  }

  public function getMailTags() {
    $tags = parent::getMailTags();

    switch ($this->getTransactionType()) {
      case PhorgeTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      case PhorgeCountdownTitleTransaction::TRANSACTIONTYPE:
      case PhorgeCountdownEpochTransaction::TRANSACTIONTYPE:
      case PhorgeCountdownDescriptionTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_DETAILS;
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }

    return $tags;
  }
}
