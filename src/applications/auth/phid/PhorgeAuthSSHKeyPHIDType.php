<?php

final class PhorgeAuthSSHKeyPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'AKEY';

  public function getTypeName() {
    return pht('Public SSH Key');
  }

  public function newObject() {
    return new PhorgeAuthSSHKey();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeAuthSSHKeyQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {
    foreach ($handles as $phid => $handle) {
      $key = $objects[$phid];
      $handle->setName(pht('SSH Key %d', $key->getID()));

      if (!$key->getIsActive()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

}
