<?php

final class PhortunePaymentProviderPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PHPR';

  public function getTypeName() {
    return pht('Phortune Payment Provider');
  }

  public function newObject() {
    return new PhortunePaymentProviderConfig();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhortunePaymentProviderConfigQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $provider_config = $objects[$phid];

      $id = $provider_config->getID();

      $handle->setName($provider_config->buildProvider()->getName());
    }
  }

}
