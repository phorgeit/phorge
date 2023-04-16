<?php

final class PhorgeOAuthServerClientAuthorizationPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'OASA';

  public function getTypeName() {
    return pht('OAuth Authorization');
  }

  public function newObject() {
    return new PhorgeOAuthClientAuthorization();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeOAuthServerApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeOAuthClientAuthorizationQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $authorization = $objects[$phid];
      $handle->setName(pht('Authorization %d', $authorization->getID()));
    }
  }

}
