<?php

final class DoorkeeperExternalObjectPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'XOBJ';

  public function getTypeName() {
    return pht('External Object');
  }

  public function newObject() {
    return new DoorkeeperExternalObject();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDoorkeeperApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DoorkeeperExternalObjectQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $xobj = $objects[$phid];

      $uri = $xobj->getObjectURI();
      $name = $xobj->getDisplayName();
      $full_name = $xobj->getDisplayFullName();

      $handle
        ->setURI($uri)
        ->setName($name)
        ->setFullName($full_name);
    }
  }

}
