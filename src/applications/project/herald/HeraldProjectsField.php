<?php

final class HeraldProjectsField
  extends PhorgeProjectTagsField {

  const FIELDCONST = 'projects';

  public function getHeraldFieldName() {
    return pht('Project tags');
  }

  public function getHeraldFieldValue($object) {
    return PhorgeEdgeQuery::loadDestinationPHIDs(
      $object->getPHID(),
      PhorgeProjectObjectHasProjectEdgeType::EDGECONST);
  }

}
