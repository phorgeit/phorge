<?php

final class ManiphestTaskRelationshipSource
  extends PhorgeObjectRelationshipSource {

  public function isEnabledForObject($object) {
    $viewer = $this->getViewer();

    return PhorgeApplication::isClassInstalledForViewer(
      'PhorgeManiphestApplication',
      $viewer);
  }

  public function getResultPHIDTypes() {
    return array(
      ManiphestTaskPHIDType::TYPECONST,
    );
  }

}
