<?php

final class PhorgeMetaMTAMailPHIDType extends PhorgePHIDType {

  const TYPECONST = 'MTAM';

  public function getTypeName() {
    return pht('MetaMTA Mail');
  }

  public function newObject() {
    return new PhorgeMetaMTAMail();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeMetaMTAApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeMetaMTAMailQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $mail = $objects[$phid];

      $id = $mail->getID();
      $name = pht('Mail %d', $id);

      $handle
        ->setName($name)
        ->setURI('/mail/detail/'.$id.'/');
    }
  }
}
