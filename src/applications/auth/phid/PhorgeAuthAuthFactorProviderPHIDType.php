<?php

final class PhorgeAuthAuthFactorProviderPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'FPRV';

  public function getTypeName() {
    return pht('MFA Provider');
  }

  public function newObject() {
    return new PhorgeAuthFactorProvider();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeAuthFactorProviderQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $provider = $objects[$phid];

      $handle->setURI($provider->getURI());
    }
  }

}
