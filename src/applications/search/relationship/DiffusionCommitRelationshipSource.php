<?php

final class DiffusionCommitRelationshipSource
  extends PhorgeObjectRelationshipSource {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    return PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDiffusionApplication',
      $viewer);
  }

  public function getResultPHIDTypes() {
    return array(
      PhorgeRepositoryCommitPHIDType::TYPECONST,
    );
  }

  public function getFilters() {
    $filters = parent::getFilters();
    unset($filters['assigned']);
    return $filters;
  }

}
