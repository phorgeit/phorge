<?php

final class PhorgePeopleExternalIdentifierPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'XIDT';

  public function getTypeName() {
    return pht('External Account Identifier');
  }

  public function newObject() {
    return new PhorgeExternalAccountIdentifier();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePeopleApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeExternalAccountIdentifierQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $identifier = $objects[$phid];
    }
  }

}
