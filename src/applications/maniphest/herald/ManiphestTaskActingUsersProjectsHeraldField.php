<?php

final class ManiphestTaskActingUsersProjectsHeraldField
  extends ManiphestTaskHeraldField {

  const FIELDCONST = 'herald.acting-user.projects';

  public function getHeraldFieldName() {
    return pht("Acting user's projects");
  }

  public function getHeraldFieldValue($object) {
    $adapter = $this->getAdapter();
    $viewer = $adapter->getViewer();

    $actor_phid = $this->getAdapter()->getActingAsPHID();
    if (!$actor_phid) {
      return array();
    }

    $projects = id(new PhabricatorProjectQuery())
      ->setViewer($viewer)
      ->withMemberPHIDs(array($actor_phid))
      ->withIsMilestone(false)
      ->execute();

    return mpull($projects, 'getPHID');
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    // Prevent selecting milestones as they cannot have members
    return id(new PhabricatorProjectDatasource())->setParameters(
      array(
        'policy' => 1,
      ));
  }

  public function getFieldGroupKey() {
    return HeraldSupportFieldGroup::FIELDGROUPKEY;
  }

}
