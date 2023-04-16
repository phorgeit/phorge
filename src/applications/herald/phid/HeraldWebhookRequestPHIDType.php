<?php

final class HeraldWebhookRequestPHIDType extends PhorgePHIDType {

  const TYPECONST = 'HWBR';

  public function getTypeName() {
    return pht('Webhook Request');
  }

  public function newObject() {
    return new HeraldWebhook();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeHeraldApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new HeraldWebhookRequestQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $request = $objects[$phid];
      $handle->setName(pht('Webhook Request %d', $request->getID()));
    }
  }

}
