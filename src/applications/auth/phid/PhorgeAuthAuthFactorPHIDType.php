<?php

final class PhorgeAuthAuthFactorPHIDType extends PhorgePHIDType {

  const TYPECONST = 'AFTR';

  public function getTypeName() {
    return pht('Auth Factor');
  }

  public function newObject() {
    return new PhorgeAuthFactorConfig();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    // TODO: Maybe we need this eventually?
    throw new PhutilMethodNotImplementedException();
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $factor = $objects[$phid];

      $handle->setName($factor->getFactorName());
    }
  }

}
