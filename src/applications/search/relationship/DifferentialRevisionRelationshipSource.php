<?php

final class DifferentialRevisionRelationshipSource
  extends PhorgeObjectRelationshipSource {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    return PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDifferentialApplication',
      $viewer);
  }

  public function getResultPHIDTypes() {
    return array(
      DifferentialRevisionPHIDType::TYPECONST,
    );
  }

}
