<?php

final class PhorgeProjectTagsAddedField
  extends PhorgeProjectTagsField {

  const FIELDCONST = 'projects.added';

  public function getHeraldFieldName() {
    return pht('Project tags added');
  }

  public function getHeraldFieldValue($object) {
    $xaction = $this->getProjectTagsTransaction();
    if (!$xaction) {
      return array();
    }

    $record = PhorgeEdgeChangeRecord::newFromTransaction($xaction);

    return $record->getAddedPHIDs();
  }

}
