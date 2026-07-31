<?php

final class ManiphestTransaction
  extends PhabricatorModularTransaction {

  const MAILTAG_STATUS = 'maniphest-status';
  const MAILTAG_OWNER = 'maniphest-owner';
  const MAILTAG_PRIORITY = 'maniphest-priority';
  const MAILTAG_CC = 'maniphest-cc';
  const MAILTAG_PROJECTS = 'maniphest-projects';
  const MAILTAG_COMMENT = 'maniphest-comment';
  const MAILTAG_COLUMN = 'maniphest-column';
  const MAILTAG_UNBLOCK = 'maniphest-unblock';
  const MAILTAG_OTHER = 'maniphest-other';


  public function getApplicationName() {
    return 'maniphest';
  }

  public function getApplicationTransactionType() {
    return ManiphestTaskPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new ManiphestTransactionComment();
  }

  public function getBaseTransactionClass() {
    return ManiphestTaskTransactionType::class;
  }

  public function shouldGenerateOldValue() {
    switch ($this->getTransactionType()) {
      case ManiphestTaskEdgeTransaction::TRANSACTIONTYPE:
      case ManiphestTaskUnblockTransaction::TRANSACTIONTYPE:
        return false;
    }

    return parent::shouldGenerateOldValue();
  }

  public function getTitle() {
    $author_phid = $this->getAuthorPHID();

    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case PhabricatorTransactions::TYPE_SUBTYPE:
        return pht(
          '%s changed the subtype of this task from "%s" to "%s".',
          $this->renderHandleLink($author_phid),
          $this->renderSubtypeName($old),
          $this->renderSubtypeName($new));
    }

    return parent::getTitle();
  }

  public function getTitleForFeed() {
    $author_phid = $this->getAuthorPHID();
    $object_phid = $this->getObjectPHID();

    $old = $this->getOldValue();
    $new = $this->getNewValue();

    switch ($this->getTransactionType()) {
      case PhabricatorTransactions::TYPE_SUBTYPE:
        return pht(
          '%s changed the subtype of %s from "%s" to "%s".',
          $this->renderHandleLink($author_phid),
          $this->renderHandleLink($object_phid),
          $this->renderSubtypeName($old),
          $this->renderSubtypeName($new));
    }

    return parent::getTitleForFeed();
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case ManiphestTaskMergedIntoTransaction::TRANSACTIONTYPE:
      case ManiphestTaskStatusTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_STATUS;
        break;
      case ManiphestTaskOwnerTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_OWNER;
        break;
      case PhabricatorTransactions::TYPE_SUBSCRIBERS:
        $tags[] = self::MAILTAG_CC;
        break;
      case PhabricatorTransactions::TYPE_EDGE:
        switch ($this->getMetadataValue('edge:type')) {
          case PhabricatorProjectObjectHasProjectEdgeType::EDGECONST:
            $tags[] = self::MAILTAG_PROJECTS;
            break;
          default:
            $tags[] = self::MAILTAG_OTHER;
            break;
        }
        break;
      case ManiphestTaskPriorityTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_PRIORITY;
        break;
      case ManiphestTaskUnblockTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_UNBLOCK;
        break;
      case ManiphestTaskColumnTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_COLUMN;
        break;
      case PhabricatorTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

  public function renderSubtypeName($value) {
    $object = $this->getObject();
    $map = $object->newEditEngineSubtypeMap();

    if (!$map->isValidSubtype($value)) {
      return $value;
    }

    return $map->getSubtype($value)->getName();
  }

}
