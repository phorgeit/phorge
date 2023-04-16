<?php

final class PhorgeConfigConfigPHIDType extends PhorgePHIDType {

  const TYPECONST = 'CONF';

  public function getTypeName() {
    return pht('Config');
  }

  public function newObject() {
    return new PhorgeConfigEntry();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeConfigApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeConfigEntryQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $entry = $objects[$phid];

      $key = $entry->getConfigKey();

      $handle->setName($key);
      $handle->setURI("/config/edit/{$key}/");
    }
  }

}
