<?php

final class AlmanacNamespacePHIDType extends PhorgePHIDType {

  const TYPECONST = 'ANAM';

  public function getTypeName() {
    return pht('Almanac Namespace');
  }

  public function newObject() {
    return new AlmanacNamespace();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAlmanacApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new AlmanacNamespaceQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $namespace = $objects[$phid];

      $id = $namespace->getID();
      $name = $namespace->getName();

      $handle->setObjectName(pht('Namespace %d', $id));
      $handle->setName($name);
      $handle->setURI($namespace->getURI());
    }
  }

}
