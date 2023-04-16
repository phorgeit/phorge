<?php

final class HarbormasterBuildStepPHIDType extends PhorgePHIDType {

  const TYPECONST = 'HMCS';

  public function getTypeName() {
    return pht('Build Step');
  }

  public function newObject() {
    return new HarbormasterBuildStep();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new HarbormasterBuildStepQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $build_step = $objects[$phid];

      $id = $build_step->getID();
      $name = $build_step->getName();

      $handle
        ->setName($name)
        ->setFullName(pht('Build Step %d: %s', $id, $name))
        ->setURI("/harbormaster/step/view/{$id}/");
    }
  }

}
