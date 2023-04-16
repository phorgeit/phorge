<?php

final class PhorgeEditEngineConfigurationPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'FORM';

  public function getTypeName() {
    return pht('Edit Configuration');
  }

  public function newObject() {
    return new PhorgeEditEngineConfiguration();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeTransactionsApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $object_query,
    array $phids) {
    return id(new PhorgeEditEngineConfigurationQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $config = $objects[$phid];

      $id = $config->getID();
      $name = $config->getName();

      $handle->setName($name);
      $handle->setURI($config->getURI());
    }
  }

}
