<?php

final class PhorgePackagesPackagePHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'PPAK';

  public function getTypeName() {
    return pht('Package');
  }

  public function newObject() {
    return new PhorgePackagesPackage();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePackagesApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgePackagesPackageQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $package = $objects[$phid];

      $name = $package->getName();
      $uri = $package->getURI();

      $handle
        ->setName($name)
        ->setURI($uri);
    }
  }

}
