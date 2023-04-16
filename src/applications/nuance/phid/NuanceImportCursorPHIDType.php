<?php

final class NuanceImportCursorPHIDType extends PhorgePHIDType {

  const TYPECONST = 'NUAC';

  public function getTypeName() {
    return pht('Import Cursor');
  }

  public function newObject() {
    return new NuanceImportCursorData();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeNuanceApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new NuanceImportCursorDataQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    $viewer = $query->getViewer();
    foreach ($handles as $phid => $handle) {
      $item = $objects[$phid];
    }
  }

}
