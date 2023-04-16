<?php

final class DrydockResourcePHIDType extends PhorgePHIDType {

  const TYPECONST = 'DRYR';

  public function getTypeName() {
    return pht('Drydock Resource');
  }

  public function getTypeIcon() {
    return 'fa-map';
  }

  public function newObject() {
    return new DrydockResource();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDrydockApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DrydockResourceQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $resource = $objects[$phid];
      $id = $resource->getID();

      $handle->setName(
        pht(
          'Resource %d: %s',
          $id,
          $resource->getResourceName()));

      $handle->setURI("/drydock/resource/{$id}/");
    }
  }

}
