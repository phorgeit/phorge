<?php

abstract class PholioMockRelationship
  extends PhorgeObjectRelationship {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    $has_app = PhorgeApplication::isClassInstalledForViewer(
      'PhorgePholioApplication',
      $viewer);
    if (!$has_app) {
      return false;
    }

    return ($object instanceof PholioMock);
  }

}
