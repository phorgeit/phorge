<?php

final class PhabricatorProjectTransaction
  extends PhabricatorModularTransaction {

  const MAILTAG_METADATA    = 'project-metadata';
  const MAILTAG_MEMBERS     = 'project-members';
  const MAILTAG_WATCHERS    = 'project-watchers';
  const MAILTAG_OTHER       = 'project-other';

  public function getApplicationName() {
    return 'project';
  }

  public function getApplicationTransactionType() {
    return PhabricatorProjectProjectPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return PhabricatorProjectTransactionType::class;
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case PhabricatorProjectNameTransaction::TRANSACTIONTYPE:
      case PhabricatorProjectSlugsTransaction::TRANSACTIONTYPE:
      case PhabricatorProjectImageTransaction::TRANSACTIONTYPE:
      case PhabricatorProjectIconTransaction::TRANSACTIONTYPE:
      case PhabricatorProjectColorTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_METADATA;
        break;
      case PhabricatorTransactions::TYPE_EDGE:
        $type = $this->getMetadata('edge:type');
        $type = head($type);
        $type_member = PhabricatorProjectProjectHasMemberEdgeType::EDGECONST;
        $type_watcher = PhabricatorObjectHasWatcherEdgeType::EDGECONST;
        if ($type == $type_member) {
          $tags[] = self::MAILTAG_MEMBERS;
        } else if ($type == $type_watcher) {
          $tags[] = self::MAILTAG_WATCHERS;
        } else {
          $tags[] = self::MAILTAG_OTHER;
        }
        break;
      case PhabricatorProjectStatusTransaction::TRANSACTIONTYPE:
      case PhabricatorProjectLockTransaction::TRANSACTIONTYPE:
      default:
        $tags[] = self::MAILTAG_OTHER;
        break;
    }
    return $tags;
  }

}
