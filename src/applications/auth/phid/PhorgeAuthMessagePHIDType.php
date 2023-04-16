<?php

final class PhorgeAuthMessagePHIDType extends PhorgePHIDType {

  const TYPECONST = 'AMSG';

  public function getTypeName() {
    return pht('Auth Message');
  }

  public function newObject() {
    return new PhorgeAuthMessage();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeAuthMessageQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $message = $objects[$phid];
      $handle->setURI($message->getURI());
    }
  }

}
