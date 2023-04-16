<?php

final class HeraldWebhookPHIDType extends PhorgePHIDType {

  const TYPECONST = 'HWBH';

  public function getTypeName() {
    return pht('Webhook');
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

    return id(new HeraldWebhookQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $hook = $objects[$phid];

      $name = $hook->getName();
      $id = $hook->getID();

      $handle
        ->setName($name)
        ->setURI($hook->getURI())
        ->setFullName(pht('Webhook %d %s', $id, $name));

      if ($hook->isDisabled()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

}
