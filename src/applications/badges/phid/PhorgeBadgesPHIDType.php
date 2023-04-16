<?php

final class PhorgeBadgesPHIDType extends PhorgePHIDType {

  const TYPECONST = 'BDGE';

  public function getTypeName() {
    return pht('Badge');
  }

  public function newObject() {
    return new PhorgeBadgesBadge();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeBadgesApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeBadgesQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $badge = $objects[$phid];

      $id = $badge->getID();
      $name = $badge->getName();

      if ($badge->isArchived()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }

      $handle->setName($name);
      $handle->setURI("/badges/view/{$id}/");
    }
  }

}
