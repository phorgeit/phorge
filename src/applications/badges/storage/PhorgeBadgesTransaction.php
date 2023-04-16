<?php

final class PhorgeBadgesTransaction
  extends PhorgeModularTransaction {

  const MAILTAG_DETAILS = 'badges:details';
  const MAILTAG_COMMENT = 'badges:comment';
  const MAILTAG_OTHER  = 'badges:other';

  public function getApplicationName() {
    return 'badges';
  }

  public function getApplicationTransactionType() {
    return PhorgeBadgesPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeBadgesTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgeBadgesBadgeTransactionType';
  }

  public function getMailTags() {
    $tags = parent::getMailTags();

    switch ($this->getTransactionType()) {
      case PhorgeTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      case PhorgeBadgesBadgeNameTransaction::TRANSACTIONTYPE:
      case PhorgeBadgesBadgeDescriptionTransaction::TRANSACTIONTYPE:
      case PhorgeBadgesBadgeFlavorTransaction::TRANSACTIONTYPE:
      case PhorgeBadgesBadgeIconTransaction::TRANSACTIONTYPE:
      case PhorgeBadgesBadgeStatusTransaction::TRANSACTIONTYPE:
      case PhorgeBadgesBadgeQualityTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_DETAILS;
        break;
      case PhorgeBadgesBadgeAwardTransaction::TRANSACTIONTYPE:
      case PhorgeBadgesBadgeRevokeTransaction::TRANSACTIONTYPE:
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

}
