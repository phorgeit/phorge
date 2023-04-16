<?php

final class AlmanacBindingPHIDType extends PhorgePHIDType {

  const TYPECONST = 'ABND';

  public function getTypeName() {
    return pht('Almanac Binding');
  }

  public function newObject() {
    return new AlmanacBinding();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAlmanacApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new AlmanacBindingQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $binding = $objects[$phid];

      $id = $binding->getID();

      $handle->setObjectName(pht('Binding %d', $id));
      $handle->setName(pht('Binding %d', $id));
    }
  }

}
