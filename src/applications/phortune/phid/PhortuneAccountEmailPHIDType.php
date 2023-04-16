<?php

final class PhortuneAccountEmailPHIDType extends PhorgePHIDType {

  const TYPECONST = 'AEML';

  public function getTypeName() {
    return pht('Phortune Account Email');
  }

  public function newObject() {
    return new PhortuneAccountEmail();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhortuneAccountEmailQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $email = $objects[$phid];

      $id = $email->getID();

      $handle->setName($email->getObjectName());
    }
  }

}
