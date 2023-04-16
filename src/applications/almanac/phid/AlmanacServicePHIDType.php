<?php

final class AlmanacServicePHIDType extends PhorgePHIDType {

  const TYPECONST = 'ASRV';

  public function getTypeName() {
    return pht('Almanac Service');
  }

  public function newObject() {
    return new AlmanacService();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAlmanacApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new AlmanacServiceQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $service = $objects[$phid];

      $id = $service->getID();
      $name = $service->getName();

      $handle->setObjectName(pht('Service %d', $id));
      $handle->setName($name);
      $handle->setURI($service->getURI());
    }
  }

}
