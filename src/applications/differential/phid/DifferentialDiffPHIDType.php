<?php

final class DifferentialDiffPHIDType extends PhorgePHIDType {

  const TYPECONST = 'DIFF';

  public function getTypeName() {
    return pht('Differential Diff');
  }

  public function newObject() {
    return new DifferentialDiff();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDifferentialApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DifferentialDiffQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $diff = $objects[$phid];

      $id = $diff->getID();

      $handle->setName(pht('Diff %d', $id));
      $handle->setURI("/differential/diff/{$id}/");
    }
  }

}
