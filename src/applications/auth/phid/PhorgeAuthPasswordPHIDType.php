<?php

final class PhorgeAuthPasswordPHIDType extends PhorgePHIDType {

  const TYPECONST = 'APAS';

  public function getTypeName() {
    return pht('Auth Password');
  }

  public function newObject() {
    return new PhorgeAuthPassword();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {
    return id(new PhorgeAuthPasswordQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $password = $objects[$phid];
    }
  }

}
