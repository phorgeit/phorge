<?php

final class PhorgePackagesPublisherPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'PPUB';

  public function getTypeName() {
    return pht('Package Publisher');
  }

  public function newObject() {
    return new PhorgePackagesPublisher();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePackagesApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgePackagesPublisherQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $publisher = $objects[$phid];

      $name = $publisher->getName();
      $uri = $publisher->getURI();

      $handle
        ->setName($name)
        ->setURI($uri);
    }
  }

}
