<?php

final class PhorgePackagesVersionPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'PVER';

  public function getTypeName() {
    return pht('Version');
  }

  public function newObject() {
    return new PhorgePackagesVersion();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePackagesApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgePackagesVersionQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $version = $objects[$phid];

      $name = $version->getName();
      $uri = $version->getURI();

      $handle
        ->setName($name)
        ->setURI($uri);
    }
  }

}
