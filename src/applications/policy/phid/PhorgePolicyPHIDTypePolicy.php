<?php

final class PhorgePolicyPHIDTypePolicy extends PhorgePHIDType {

  const TYPECONST = 'PLCY';

  public function getTypeName() {
    return pht('Policy');
  }

  public function newObject() {
    return new PhorgePolicy();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePolicyApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgePolicyQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $policy = $objects[$phid];

      $handle->setName($policy->getName());
      $handle->setURI($policy->getHref());
    }
  }

}
