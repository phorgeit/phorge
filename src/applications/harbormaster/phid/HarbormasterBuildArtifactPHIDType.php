<?php

final class HarbormasterBuildArtifactPHIDType extends PhorgePHIDType {

  const TYPECONST = 'HMBA';

  public function getTypeName() {
    return pht('Build Artifact');
  }

  public function newObject() {
    return new HarbormasterBuildArtifact();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new HarbormasterBuildArtifactQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $artifact = $objects[$phid];
      $artifact_id = $artifact->getID();
      $handle->setName(pht('Build Artifact %d', $artifact_id));
    }
  }

}
