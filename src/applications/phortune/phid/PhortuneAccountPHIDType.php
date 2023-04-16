<?php

final class PhortuneAccountPHIDType extends PhorgePHIDType {

  const TYPECONST = 'ACNT';

  public function getTypeName() {
    return pht('Phortune Account');
  }

  public function newObject() {
    return new PhortuneAccount();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhortuneAccountQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $account = $objects[$phid];

      $handle
        ->setName($account->getName())
        ->setURI($account->getURI());
    }
  }

}
