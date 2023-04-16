<?php

abstract class DifferentialRevisionRelationship
  extends PhorgeObjectRelationship {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    $has_app = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDifferentialApplication',
      $viewer);
    if (!$has_app) {
      return false;
    }

    return ($object instanceof DifferentialRevision);
  }

}
