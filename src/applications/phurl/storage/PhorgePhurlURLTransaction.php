<?php

final class PhorgePhurlURLTransaction
  extends PhorgeModularTransaction {

  const MAILTAG_DETAILS = 'phurl-details';

  public function getApplicationName() {
    return 'phurl';
  }

  public function getApplicationTransactionType() {
    return PhorgePhurlURLPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgePhurlURLTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgePhurlURLTransactionType';
  }

  public function getRequiredHandlePHIDs() {
    $phids = parent::getRequiredHandlePHIDs();

    switch ($this->getTransactionType()) {
      case PhorgePhurlURLNameTransaction::TRANSACTIONTYPE:
      case PhorgePhurlURLLongURLTransaction::TRANSACTIONTYPE:
      case PhorgePhurlURLAliasTransaction::TRANSACTIONTYPE:
      case PhorgePhurlURLDescriptionTransaction::TRANSACTIONTYPE:
        $phids[] = $this->getObjectPHID();
        break;
    }

    return $phids;
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case PhorgePhurlURLNameTransaction::TRANSACTIONTYPE:
      case PhorgePhurlURLLongURLTransaction::TRANSACTIONTYPE:
      case PhorgePhurlURLAliasTransaction::TRANSACTIONTYPE:
      case PhorgePhurlURLDescriptionTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_DETAILS;
        break;
    }
    return $tags;
  }

}
