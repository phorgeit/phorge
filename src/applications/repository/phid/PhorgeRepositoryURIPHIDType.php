<?php

final class PhorgeRepositoryURIPHIDType extends PhorgePHIDType {

  const TYPECONST = 'RURI';

  public function getTypeName() {
    return pht('Repository URI');
  }

  public function newObject() {
    return new PhorgeRepositoryURI();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositoryURIQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $uri = $objects[$phid];

      $handle->setName(
        pht('URI %d %s', $uri->getID(), $uri->getDisplayURI()));
    }
  }

}
