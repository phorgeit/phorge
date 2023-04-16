<?php

final class PhorgeCalendarExportPHIDType extends PhorgePHIDType {

  const TYPECONST = 'CEXP';

  public function getTypeName() {
    return pht('Calendar Export');
  }

  public function newObject() {
    return new PhorgeCalendarExport();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeCalendarExportQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $export = $objects[$phid];

      $id = $export->getID();
      $name = $export->getName();
      $uri = $export->getURI();

      $handle
        ->setName($name)
        ->setFullName(pht('Calendar Export %s: %s', $id, $name))
        ->setURI($uri);

      if ($export->getIsDisabled()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

}
