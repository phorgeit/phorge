<?php

final class PhorgePeopleUserEmailPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'EADR';

  public function getTypeName() {
    return pht('User Email');
  }

  public function newObject() {
    return new PhorgeUserEmail();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePeopleApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgePeopleUserEmailQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $email = $objects[$phid];
      $handle->setName($email->getAddress());
    }

    return null;
  }

}
