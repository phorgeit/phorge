<?php

final class DrydockAuthorizationPHIDType extends PhorgePHIDType {

  const TYPECONST = 'DRYA';

  public function getTypeName() {
    return pht('Drydock Authorization');
  }

  public function newObject() {
    return new DrydockAuthorization();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDrydockApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DrydockAuthorizationQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $authorization = $objects[$phid];
      $id = $authorization->getID();

      $handle->setName(pht('Drydock Authorization %d', $id));
      $handle->setURI("/drydock/authorization/{$id}/");
    }
  }

}
