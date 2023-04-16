<?php

final class PhorgeAuthSessionPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'SSSN';

  public function getTypeName() {
    return pht('Session');
  }

  public function newObject() {
    return new PhorgeAuthSession();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {
    return id(new PhorgeAuthSessionQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {
    return;
  }

}
