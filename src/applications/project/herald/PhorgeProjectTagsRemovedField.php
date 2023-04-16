<?php

final class PhorgeProjectTagsRemovedField
  extends PhorgeProjectTagsField {

  const FIELDCONST = 'projects.removed';

  public function getHeraldFieldName() {
    return pht('Project tags removed');
  }

  public function getHeraldFieldValue($object) {
    $xaction = $this->getProjectTagsTransaction();
    if (!$xaction) {
      return array();
    }

    $record = PhorgeEdgeChangeRecord::newFromTransaction($xaction);

    return $record->getRemovedPHIDs();
  }

}
