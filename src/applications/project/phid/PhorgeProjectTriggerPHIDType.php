<?php

final class PhorgeProjectTriggerPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'WTRG';

  public function getTypeName() {
    return pht('Trigger');
  }

  public function getTypeIcon() {
    return 'fa-exclamation-triangle';
  }

  public function newObject() {
    return new PhorgeProjectTrigger();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeProjectApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeProjectTriggerQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $trigger = $objects[$phid];

      $handle->setName($trigger->getDisplayName());
      $handle->setURI($trigger->getURI());
    }
  }

}
