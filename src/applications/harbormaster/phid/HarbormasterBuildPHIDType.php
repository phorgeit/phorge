<?php

final class HarbormasterBuildPHIDType extends PhorgePHIDType {

  const TYPECONST = 'HMBD';

  public function getTypeName() {
    return pht('Build');
  }

  public function newObject() {
    return new HarbormasterBuild();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new HarbormasterBuildQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $build = $objects[$phid];
      $build_id = $build->getID();
      $name = $build->getName();

      $handle->setName(pht('Build %d: %s', $build_id, $name));
      $handle->setURI("/harbormaster/build/{$build_id}/");
    }
  }

}
