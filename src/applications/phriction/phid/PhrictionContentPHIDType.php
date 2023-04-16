<?php

final class PhrictionContentPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'WRDS';

  public function getTypeName() {
    return pht('Phriction Content');
  }

  public function newObject() {
    return new PhrictionContent();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhrictionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhrictionContentQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $content = $objects[$phid];
    }
  }

}
