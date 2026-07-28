<?php

final class PhrictionTransaction
  extends PhabricatorModularTransaction {

  const MAILTAG_TITLE       = 'phriction-title';
  const MAILTAG_CONTENT     = 'phriction-content';
  const MAILTAG_DELETE      = 'phriction-delete';
  const MAILTAG_SUBSCRIBERS = 'phriction-subscribers';
  const MAILTAG_OTHER       = 'phriction-other';

  public function getApplicationName() {
    return 'phriction';
  }

  public function getApplicationTransactionType() {
    return PhrictionDocumentPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhrictionTransactionComment();
  }

  public function getBaseTransactionClass() {
    return PhrictionDocumentTransactionType::class;
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case PhrictionDocumentTitleTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_TITLE;
        break;
      case PhrictionDocumentContentTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_CONTENT;
        break;
      case PhrictionDocumentDeleteTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_DELETE;
        break;
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
        $tags[] = self::MAILTAG_SUBSCRIBERS;
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

}
