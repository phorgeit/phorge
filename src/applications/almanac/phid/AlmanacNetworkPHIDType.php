<?php

final class AlmanacNetworkPHIDType extends PhorgePHIDType {

  const TYPECONST = 'ANET';

  public function getTypeName() {
    return pht('Almanac Network');
  }

  public function newObject() {
    return new AlmanacNetwork();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAlmanacApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new AlmanacNetworkQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $network = $objects[$phid];

      $id = $network->getID();
      $name = $network->getName();

      $handle->setObjectName(pht('Network %d', $id));
      $handle->setName($name);
      $handle->setURI($network->getURI());
    }
  }

}
