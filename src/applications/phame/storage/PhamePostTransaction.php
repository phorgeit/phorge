<?php

final class PhamePostTransaction
  extends PhorgeModularTransaction {

  const MAILTAG_CONTENT       = 'phame-post-content';
  const MAILTAG_SUBSCRIBERS   = 'phame-post-subscribers';
  const MAILTAG_COMMENT       = 'phame-post-comment';
  const MAILTAG_OTHER         = 'phame-post-other';

  public function getApplicationName() {
    return 'phame';
  }

  public function getApplicationTransactionType() {
    return PhorgePhamePostPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhamePostTransactionType';
  }

  public function getApplicationTransactionCommentObject() {
    return new PhamePostTransactionComment();
  }

  public function getMailTags() {
    $tags = parent::getMailTags();

    switch ($this->getTransactionType()) {
      case PhorgeTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      case PhorgeTransactions::TYPE_SUBSCRIBERS:
        $tags[] = self::MAILTAG_SUBSCRIBERS;
        break;
      case PhamePostTitleTransaction::TRANSACTIONTYPE:
      case PhamePostSubtitleTransaction::TRANSACTIONTYPE:
      case PhamePostBodyTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_CONTENT;
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

}
