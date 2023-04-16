<?php

final class PhorgeProfileMenuItemPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'PANL';

  public function getTypeName() {
    return pht('Profile Menu Item');
  }

  public function newObject() {
    return new PhorgeProfileMenuItemConfiguration();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeSearchApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $object_query,
    array $phids) {
    return id(new PhorgeProfileMenuItemConfigurationQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $config = $objects[$phid];

      $handle->setName(pht('Profile Menu Item'));
    }
  }

}
