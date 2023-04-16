<?php

final class PhorgePeopleExternalPHIDType extends PhorgePHIDType {

  const TYPECONST = 'XUSR';

  public function getTypeName() {
    return pht('External Account');
  }

  public function newObject() {
    return new PhorgeExternalAccount();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePeopleApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeExternalAccountQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $account = $objects[$phid];

      $display_name = $account->getDisplayName();
      $handle->setName($display_name);
    }
  }

}
