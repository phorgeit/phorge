<?php

final class DiffusionPreCommitRefRepositoryProjectsHeraldField
  extends DiffusionPreCommitRefHeraldField {

  const FIELDCONST = 'diffusion.pre.ref.repository.projects';

  public function getHeraldFieldName() {
    return pht('Repository projects');
  }

  public function getHeraldFieldValue($object) {
    return PhorgeEdgeQuery::loadDestinationPHIDs(
      $this->getAdapter()->getHookEngine()->getRepository()->getPHID(),
      PhorgeProjectObjectHasProjectEdgeType::EDGECONST);
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new PhorgeProjectDatasource();
  }

}
