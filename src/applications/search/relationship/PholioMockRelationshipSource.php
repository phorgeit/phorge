<?php

final class PholioMockRelationshipSource
  extends PhorgeObjectRelationshipSource {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    return PhorgeApplication::isClassInstalledForViewer(
      'PhorgePholioApplication',
      $viewer);
  }

  public function getResultPHIDTypes() {
    return array(
      PholioMockPHIDType::TYPECONST,
    );
  }

  public function getFilters() {
    $filters = parent::getFilters();
    unset($filters['assigned']);
    return $filters;
  }

}
