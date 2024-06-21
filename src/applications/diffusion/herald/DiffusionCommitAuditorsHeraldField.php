<?php

final class DiffusionCommitAuditorsHeraldField
  extends DiffusionCommitHeraldField {

  const FIELDCONST = 'diffusion.commit.auditors';

  // hide "Auditors" Herald condition if Audit not installed
  public function supportsObject($object) {
    if (id(new PhabricatorAuditApplication())->isInstalled()) {
      return ($object instanceof PhabricatorRepositoryCommit);
    } else {
      return false;
    }
  }

  public function getHeraldFieldName() {
    return pht('Auditors');
  }

  public function getHeraldFieldValue($object) {
    $viewer = PhabricatorUser::getOmnipotentUser();

    $commit = id(new DiffusionCommitQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($object->getPHID()))
      ->needAuditRequests(true)
      ->executeOne();

    $audits = $commit->getAudits();

    $phids = array();
    foreach ($audits as $audit) {
      if ($audit->isResigned()) {
        continue;
      }

      $phids[] = $audit->getAuditorPHID();
    }

    return $phids;
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new DiffusionAuditorDatasource();
  }

}
