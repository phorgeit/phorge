<?php

final class AlmanacDevicePHIDType extends PhorgePHIDType {

  const TYPECONST = 'ADEV';

  public function getTypeName() {
    return pht('Almanac Device');
  }

  public function newObject() {
    return new AlmanacDevice();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAlmanacApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new AlmanacDeviceQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $device = $objects[$phid];

      $id = $device->getID();
      $name = $device->getName();

      $handle->setObjectName(pht('Device %d', $id));
      $handle->setName($name);
      $handle->setURI($device->getURI());
    }
  }

}
