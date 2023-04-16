<?php

final class PhorgeRepositoryRefCursorPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'RREF';

  public function getTypeName() {
    return pht('Repository Ref');
  }

  public function getTypeIcon() {
    return 'fa-code-fork';
  }

  public function newObject() {
    return new PhorgeRepositoryRefCursor();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositoryRefCursorQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $ref = $objects[$phid];

      $name = $ref->getRefName();

      $handle->setName($name);
    }
  }

}
