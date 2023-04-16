<?php

final class PhortunePaymentMethodPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PAYM';

  public function getTypeName() {
    return pht('Phortune Payment Method');
  }

  public function newObject() {
    return new PhortunePaymentMethod();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhortunePaymentMethodQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $method = $objects[$phid];

      $handle
        ->setName($method->getFullDisplayName())
        ->setURI($method->getURI());
    }
  }

}
