<?php

final class PhorgeRepositorySyncEventPHIDType extends PhorgePHIDType {

  const TYPECONST = 'SYNE';

  public function getTypeName() {
    return pht('Sync Event');
  }

  public function newObject() {
    return new PhorgeRepositorySyncEvent();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositorySyncEventQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $event = $objects[$phid];

      $handle->setName(pht('Sync Event %d', $event->getID()));
    }
  }

}
