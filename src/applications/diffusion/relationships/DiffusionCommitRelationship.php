<?php

abstract class DiffusionCommitRelationship
  extends PhorgeObjectRelationship {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    $has_app = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDiffusionApplication',
      $viewer);
    if (!$has_app) {
      return false;
    }

    return ($object instanceof PhorgeRepositoryCommit);
  }

}
