<?php

final class PhorgeAuthChallengePHIDType extends PhorgePHIDType {

  const TYPECONST = 'CHAL';

  public function getTypeName() {
    return pht('Auth Challenge');
  }

  public function newObject() {
    return new PhorgeAuthChallenge();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {
    return new PhorgeAuthChallengeQuery();
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {
    return;
  }

}
