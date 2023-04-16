<?php

final class DifferentialDiffRepositoryProjectsHeraldField
  extends DifferentialDiffHeraldField {

  const FIELDCONST = 'differential.diff.repository.projects';

  public function getHeraldFieldName() {
    return pht('Repository projects');
  }

  public function getHeraldFieldValue($object) {
    $repository = $this->getAdapter()->loadRepository();
    if (!$repository) {
      return array();
    }

    return PhorgeEdgeQuery::loadDestinationPHIDs(
      $repository->getPHID(),
      PhorgeProjectObjectHasProjectEdgeType::EDGECONST);
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new PhorgeProjectDatasource();
  }

}
