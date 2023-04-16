<?php

final class PhorgeMetaMTAApplicationEmailPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'APPE';

  public function getTypeName() {
    return pht('Application Email');
  }

  public function getTypeIcon() {
    return 'fa-email bluegrey';
  }

  public function newObject() {
    return new PhorgeMetaMTAApplicationEmail();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeMetaMTAApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeMetaMTAApplicationEmailQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $email = $objects[$phid];

      $handle->setName($email->getAddress());
      $handle->setFullName($email->getAddress());
    }
  }
}
