<?php

final class PhorgeRepositoryPushEventPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PSHE';

  public function getTypeName() {
    return pht('Push Event');
  }

  public function newObject() {
    return new PhorgeRepositoryPushEvent();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositoryPushEventQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $event = $objects[$phid];

      $handle->setName(pht('Push Event %d', $event->getID()));
    }
  }

}
