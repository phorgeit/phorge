<?php

final class PhorgeTokenTokenPHIDType extends PhorgePHIDType {

  const TYPECONST = 'TOKN';

  public function getTypeName() {
    return pht('Token');
  }

  public function newObject() {
    return new PhorgeToken();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeTokensApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeTokenQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $token = $objects[$phid];

      $name = $token->getName();

      $handle->setName(pht('%s Token', $name));
    }
  }

}
