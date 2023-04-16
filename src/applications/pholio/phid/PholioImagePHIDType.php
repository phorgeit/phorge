<?php

final class PholioImagePHIDType extends PhorgePHIDType {

  const TYPECONST = 'PIMG';

  public function getTypeName() {
    return pht('Image');
  }

  public function newObject() {
    return new PholioImage();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePholioApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PholioImageQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $image = $objects[$phid];

      $handle
        ->setName($image->getName())
        ->setURI($image->getURI());
    }
  }

}
