<?php

final class PhorgeDashboardDashboardPHIDType extends PhorgePHIDType {

  const TYPECONST = 'DSHB';

  public function getTypeName() {
    return pht('Dashboard');
  }

  public function newObject() {
    return new PhorgeDashboard();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeDashboardQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $dashboard = $objects[$phid];

      $id = $dashboard->getID();

      $handle->setName($dashboard->getName());
      $handle->setURI("/dashboard/view/{$id}/");
    }
  }

}
