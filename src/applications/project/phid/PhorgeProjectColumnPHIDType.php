<?php

final class PhorgeProjectColumnPHIDType extends PhorgePHIDType {

  const TYPECONST = 'PCOL';

  public function getTypeName() {
    return pht('Project Column');
  }

  public function getTypeIcon() {
    return 'fa-columns bluegrey';
  }

  public function newObject() {
    return new PhorgeProjectColumn();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeProjectApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeProjectColumnQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $column = $objects[$phid];

      $handle->setName($column->getDisplayName());
      $handle->setURI($column->getWorkboardURI());

      if ($column->isHidden()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

}
