<?php

final class PhorgeOAuthServerClientPHIDType extends PhorgePHIDType {

  const TYPECONST = 'OASC';

  public function getTypeName() {
    return pht('OAuth Application');
  }

  public function newObject() {
    return new PhorgeOAuthServerClient();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeOAuthServerApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeOAuthServerClientQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $client = $objects[$phid];

      $handle
        ->setName($client->getName())
        ->setURI($client->getURI());
    }
  }

}
