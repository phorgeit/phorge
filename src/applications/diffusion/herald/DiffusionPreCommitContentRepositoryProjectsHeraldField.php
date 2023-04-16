<?php

final class DiffusionPreCommitContentRepositoryProjectsHeraldField
  extends DiffusionPreCommitContentHeraldField {

  const FIELDCONST = 'diffusion.pre.content.repository.projects';

  public function getHeraldFieldName() {
    return pht('Repository projects');
  }

  public function getHeraldFieldValue($object) {
    return PhorgeEdgeQuery::loadDestinationPHIDs(
      $this->getAdapter()->getHookEngine()->getRepository()->getPHID(),
      PhorgeProjectObjectHasProjectEdgeType::EDGECONST);
  }

  protected function getHeraldFieldStandardType() {
    return HeraldField::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new PhorgeProjectDatasource();
  }

}
