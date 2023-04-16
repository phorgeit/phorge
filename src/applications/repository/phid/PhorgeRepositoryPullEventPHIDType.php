<?php

final class PhorgeRepositoryPullEventPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PULE';

  public function getTypeName() {
    return pht('Pull Event');
  }

  public function newObject() {
    return new PhorgeRepositoryPullEvent();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositoryPullEventQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $event = $objects[$phid];

      $handle->setName(pht('Pull Event %d', $event->getID()));
    }
  }

}
