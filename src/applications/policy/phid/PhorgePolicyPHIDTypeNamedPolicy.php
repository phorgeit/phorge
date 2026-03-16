<?php

final class PhorgePolicyPHIDTypeNamedPolicy extends PhabricatorPHIDType {

  const TYPECONST = 'NPLC';

  public function getTypeName() {
    return pht('Named Policy');
  }

  public function newObject() {
    return new PhorgeNamedPolicy();
  }

  public function getPHIDTypeApplicationClass() {
    return PhabricatorPolicyApplication::class;
  }

  protected function buildQueryForObjects(
    PhabricatorObjectQuery $query,
    array $phids) {

    return id(new PhorgeNamedPolicyQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhabricatorHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $policy = $objects[$phid];
      $handle->setName($policy->getName());
      $handle->setURI($policy->getHref());
    }
  }

}
