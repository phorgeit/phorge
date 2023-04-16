<?php

final class NuanceSourcePHIDType extends PhorgePHIDType {

  const TYPECONST = 'NUAS';

  public function getTypeName() {
    return pht('Source');
  }

  public function newObject() {
    return new NuanceSource();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeNuanceApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new NuanceSourceQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    $viewer = $query->getViewer();
    foreach ($handles as $phid => $handle) {
      $source = $objects[$phid];

      $handle->setName($source->getName());
      $handle->setURI($source->getURI());
    }
  }

}
