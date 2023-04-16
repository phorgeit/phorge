<?php

final class PhorgeUserPreferencesPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PSET';

  public function getTypeName() {
    return pht('Settings');
  }

  public function newObject() {
    return new PhorgeUserPreferences();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeSettingsApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeUserPreferencesQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    $viewer = $query->getViewer();
    foreach ($handles as $phid => $handle) {
      $preferences = $objects[$phid];
      $handle->setName(pht('Settings %d', $preferences->getID()));
    }
  }

}
