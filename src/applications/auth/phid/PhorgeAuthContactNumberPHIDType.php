<?php

final class PhorgeAuthContactNumberPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'CTNM';

  public function getTypeName() {
    return pht('Contact Number');
  }

  public function newObject() {
    return new PhorgeAuthContactNumber();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeAuthContactNumberQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $contact_number = $objects[$phid];
    }
  }

}
