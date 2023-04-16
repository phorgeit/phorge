<?php

final class PhorgeWorkerTriggerPHIDType extends PhorgePHIDType {

  const TYPECONST = 'TRIG';

  public function getTypeName() {
    return pht('Trigger');
  }

  public function newObject() {
    return new PhorgeWorkerTrigger();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDaemonsApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeWorkerTriggerQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $trigger = $objects[$phid];

      $id = $trigger->getID();

      $handle->setName(pht('Trigger %d', $id));
    }
  }

}
