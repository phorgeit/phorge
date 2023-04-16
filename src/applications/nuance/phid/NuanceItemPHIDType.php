<?php

final class NuanceItemPHIDType extends PhorgePHIDType {

  const TYPECONST = 'NUAI';

  public function getTypeName() {
    return pht('Item');
  }

  public function newObject() {
    return new NuanceItem();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeNuanceApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new NuanceItemQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    $viewer = $query->getViewer();
    foreach ($handles as $phid => $handle) {
      $item = $objects[$phid];

      $handle->setName($item->getDisplayName());
      $handle->setURI($item->getURI());
    }
  }

}
