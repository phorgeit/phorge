<?php

final class PhorgeAuthInvitePHIDType extends PhorgePHIDType {

  const TYPECONST = 'AINV';

  public function getTypeName() {
    return pht('Auth Invite');
  }

  public function newObject() {
    return new PhorgeAuthInvite();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {
    throw new PhutilMethodNotImplementedException();
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $invite = $objects[$phid];
    }
  }

}
