<?php

final class PhorgeWorkerBulkJobPHIDType extends PhorgePHIDType {

  const TYPECONST = 'BULK';

  public function getTypeName() {
    return pht('Bulk Job');
  }

  public function newObject() {
    return new PhorgeWorkerBulkJob();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDaemonsApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeWorkerBulkJobQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $job = $objects[$phid];

      $id = $job->getID();

      $handle->setName(pht('Bulk Job %d', $id));
    }
  }

}
