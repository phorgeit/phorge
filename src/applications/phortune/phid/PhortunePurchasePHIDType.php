<?php

final class PhortunePurchasePHIDType extends PhorgePHIDType {

  const TYPECONST = 'PRCH';

  public function getTypeName() {
    return pht('Phortune Purchase');
  }

  public function newObject() {
    return new PhortunePurchase();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhortunePurchaseQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $purchase = $objects[$phid];

      $id = $purchase->getID();

      $handle->setName($purchase->getFullDisplayName());
      $handle->setURI($purchase->getURI());
    }
  }

}
