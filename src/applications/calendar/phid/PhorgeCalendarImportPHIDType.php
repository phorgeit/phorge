<?php

final class PhorgeCalendarImportPHIDType extends PhorgePHIDType {

  const TYPECONST = 'CIMP';

  public function getTypeName() {
    return pht('Calendar Import');
  }

  public function newObject() {
    return new PhorgeCalendarImport();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeCalendarImportQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $import = $objects[$phid];

      $id = $import->getID();
      $name = $import->getDisplayName();
      $uri = $import->getURI();

      $handle
        ->setName($name)
        ->setFullName(pht('Calendar Import %s: %s', $id, $name))
        ->setURI($uri);

      if ($import->getIsDisabled()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

}
