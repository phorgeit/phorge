<?php

final class PhorgePasteTransaction
  extends PhorgeModularTransaction {

  const MAILTAG_CONTENT = 'paste-content';
  const MAILTAG_OTHER = 'paste-other';
  const MAILTAG_COMMENT = 'paste-comment';

  public function getApplicationName() {
    return 'paste';
  }

  public function getApplicationTransactionType() {
    return PhorgePastePastePHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgePasteTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgePasteTransactionType';
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case PhorgePasteTitleTransaction::TRANSACTIONTYPE:
      case PhorgePasteContentTransaction::TRANSACTIONTYPE:
      case PhorgePasteLanguageTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_CONTENT;
        break;
      case PhorgeTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

}
