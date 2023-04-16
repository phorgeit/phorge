<?php

final class PhluxVariablePHIDType extends PhorgePHIDType {

  const TYPECONST = 'PVAR';

  public function getTypeName() {
    return pht('Variable');
  }

  public function newObject() {
    return new PhluxVariable();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhluxApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhluxVariableQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $variable = $objects[$phid];

      $key = $variable->getVariableKey();

      $handle->setName($key);
      $handle->setFullName(pht('Variable "%s"', $key));
      $handle->setURI("/phlux/view/{$key}/");
    }
  }

}
