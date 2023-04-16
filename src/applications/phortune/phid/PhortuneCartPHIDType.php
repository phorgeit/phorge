<?php

final class PhortuneCartPHIDType extends PhorgePHIDType {

  const TYPECONST = 'CART';

  public function getTypeName() {
    return pht('Phortune Cart');
  }

  public function newObject() {
    return new PhortuneCart();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhortuneCartQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $cart = $objects[$phid];

      $id = $cart->getID();
      $name = $cart->getName();

      $handle->setName($name);
      $handle->setURI("/phortune/cart/{$id}/");
    }
  }

}
