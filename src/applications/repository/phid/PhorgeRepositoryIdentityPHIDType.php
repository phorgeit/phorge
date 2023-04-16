<?php

final class PhorgeRepositoryIdentityPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'RIDT';

  public function getTypeName() {
    return pht('Repository Identity');
  }

  public function newObject() {
    return new PhorgeRepositoryIdentity();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositoryIdentityQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    $avatar_uri = celerity_get_resource_uri('/rsrc/image/avatar.png');
    foreach ($handles as $phid => $handle) {
      $identity = $objects[$phid];

      $id = $identity->getID();
      $name = $identity->getIdentityNameRaw();

      $handle->setObjectName(pht('Identity %d', $id));
      $handle->setName($name);
      $handle->setURI($identity->getURI());
      $handle->setIcon('fa-user');
      $handle->setImageURI($avatar_uri);
    }
  }

}
