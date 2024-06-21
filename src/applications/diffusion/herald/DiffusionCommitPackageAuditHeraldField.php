<?php

final class DiffusionCommitPackageAuditHeraldField
  extends DiffusionCommitHeraldField {

  const FIELDCONST = 'diffusion.commit.package.audit';

  // hide "Affected packages that need audit" Herald condition
  // if Audit not installed
  public function supportsObject($object) {
    if (id(new PhabricatorAuditApplication())->isInstalled()) {
      return ($object instanceof PhabricatorRepositoryCommit);
    } else {
      return false;
    }
  }

  public function getHeraldFieldName() {
    return pht('Affected packages that need audit');
  }

  public function getFieldGroupKey() {
    return HeraldRelatedFieldGroup::FIELDGROUPKEY;
  }

  public function getHeraldFieldValue($object) {
    $packages = $this->getAdapter()->loadAuditNeededPackages();
    if (!$packages) {
      return array();
    }

    return mpull($packages, 'getPHID');
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new PhabricatorOwnersPackageDatasource();
  }

}
