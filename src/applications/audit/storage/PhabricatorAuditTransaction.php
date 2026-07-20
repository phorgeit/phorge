<?php

final class PhabricatorAuditTransaction
  extends PhabricatorModularTransaction {

  /** @deprecated move applicable code to PhorgeAuditCommitCommitTransaction */
  const TYPE_COMMIT = 'audit:commit';

  const MAILTAG_ACTION_CONCERN = 'audit-action-concern';
  const MAILTAG_ACTION_ACCEPT  = 'audit-action-accept';
  const MAILTAG_ACTION_RESIGN  = 'audit-action-resign';
  const MAILTAG_ACTION_CLOSE   = 'audit-action-close';
  const MAILTAG_ADD_AUDITORS   = 'audit-add-auditors';
  const MAILTAG_ADD_CCS        = 'audit-add-ccs';
  const MAILTAG_COMMENT        = 'audit-comment';
  const MAILTAG_COMMIT         = 'audit-commit';
  const MAILTAG_PROJECTS       = 'audit-projects';
  const MAILTAG_OTHER          = 'audit-other';

  public function getApplicationName() {
    return 'audit';
  }

  public function getBaseTransactionClass() {
    return DiffusionCommitTransactionType::class;
  }

  public function getApplicationTransactionType() {
    return PhabricatorRepositoryCommitPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhabricatorAuditTransactionComment();
  }

  public function isInlineCommentTransaction() {
    switch ($this->getTransactionType()) {
      case PhorgeAuditCommitInlineCommentTransaction::TRANSACTIONTYPE:
        return true;
    }

    return parent::isInlineCommentTransaction();
  }

  public function getBodyForMail() {
    switch ($this->getTransactionType()) {
    case PhorgeAuditCommitCommitTransaction::TRANSACTIONTYPE:
      $data = $this->getNewValue();
      return $data['description'];
    }

    return parent::getBodyForMail();
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case DiffusionCommitAcceptTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ACTION_ACCEPT;
        break;
      case DiffusionCommitConcernTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ACTION_CONCERN;
        break;
      case DiffusionCommitResignTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ACTION_RESIGN;
        break;
      case DiffusionCommitAuditorsTransaction::TRANSACTIONTYPE:
      case PhorgeAuditCommitAddAuditorTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ADD_AUDITORS;
        break;
      case PhorgeAuditCommitActionTransaction::TRANSACTIONTYPE:
        switch ($this->getNewValue()) {
          case PhabricatorAuditActionConstants::CONCERN:
            $tags[] = self::MAILTAG_ACTION_CONCERN;
            break;
          case PhabricatorAuditActionConstants::ACCEPT:
            $tags[] = self::MAILTAG_ACTION_ACCEPT;
            break;
          case PhabricatorAuditActionConstants::RESIGN:
            $tags[] = self::MAILTAG_ACTION_RESIGN;
            break;
          case PhabricatorAuditActionConstants::CLOSE:
            $tags[] = self::MAILTAG_ACTION_CLOSE;
            break;
        }
        break;
      case PhorgeAuditCommitAddCCTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_ADD_CCS;
        break;
      case PhorgeAuditCommitInlineCommentTransaction::TRANSACTIONTYPE:
      case PhabricatorTransactions::TYPE_COMMENT:
        $tags[] = self::MAILTAG_COMMENT;
        break;
      case PhorgeAuditCommitCommitTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_COMMIT;
        break;
      case PhabricatorTransactions::TYPE_EDGE:
        switch ($this->getMetadataValue('edge:type')) {
          case PhabricatorProjectObjectHasProjectEdgeType::EDGECONST:
            $tags[] = self::MAILTAG_PROJECTS;
            break;
          case PhabricatorObjectHasSubscriberEdgeType::EDGECONST:
            $tags[] = self::MAILTAG_ADD_CCS;
            break;
          default:
            $tags[] = self::MAILTAG_OTHER;
            break;
        }
        break;
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

  public function shouldDisplayGroupWith(array $group) {
    // Make the "This commit now requires audit." state message stand alone.
    $type_state = DiffusionCommitStateTransaction::TRANSACTIONTYPE;

    if ($this->getTransactionType() == $type_state) {
      return false;
    }

    foreach ($group as $xaction) {
      if ($xaction->getTransactionType() == $type_state) {
        return false;
      }
    }

    return parent::shouldDisplayGroupWith($group);
  }

}
