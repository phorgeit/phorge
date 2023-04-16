<?php

final class PhorgeAuthAuthProviderPHIDType extends PhorgePHIDType {

  const TYPECONST = 'AUTH';

  public function getTypeName() {
    return pht('Auth Provider');
  }

  public function newObject() {
    return new PhorgeAuthProviderConfig();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeAuthProviderConfigQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $provider = $objects[$phid]->getProvider();

      if ($provider) {
        $handle->setName($provider->getProviderName());
      }
    }
  }

}
