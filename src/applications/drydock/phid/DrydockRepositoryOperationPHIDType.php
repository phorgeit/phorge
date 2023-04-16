<?php

final class DrydockRepositoryOperationPHIDType extends PhorgePHIDType {

  const TYPECONST = 'DRYO';

  public function getTypeName() {
    return pht('Repository Operation');
  }

  public function newObject() {
    return new DrydockRepositoryOperation();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDrydockApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DrydockRepositoryOperationQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $operation = $objects[$phid];
      $id = $operation->getID();

      $handle->setName(pht('Repository Operation %d', $id));
      $handle->setURI("/drydock/operation/{$id}/");
    }
  }

}
