<?php

final class DifferentialChangesetPHIDType extends PhorgePHIDType {

  const TYPECONST = 'DCNG';

  public function getTypeName() {
    return pht('Differential Changeset');
  }

  public function newObject() {
    return new DifferentialChangeset();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDifferentialApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DifferentialChangesetQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $changeset = $objects[$phid];

      $id = $changeset->getID();

      $handle->setName(pht('Changeset %d', $id));
    }
  }

}
