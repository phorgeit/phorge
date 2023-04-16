<?php

final class PhorgeRepositoryPushLogPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PSHL';

  public function getTypeName() {
    return pht('Push Log');
  }

  public function newObject() {
    return new PhorgeRepositoryPushLog();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositoryPushLogQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $log = $objects[$phid];

      $handle->setName(pht('Push Log %d', $log->getID()));
    }
  }

}
