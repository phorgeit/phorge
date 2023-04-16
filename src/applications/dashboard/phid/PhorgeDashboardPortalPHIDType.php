<?php

final class PhorgeDashboardPortalPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'PRTL';

  public function getTypeName() {
    return pht('Portal');
  }

  public function newObject() {
    return new PhorgeDashboardPortal();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeDashboardPortalQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $portal = $objects[$phid];

      $handle
        ->setIcon('fa-compass')
        ->setName($portal->getName())
        ->setURI($portal->getURI());
    }
  }

}
