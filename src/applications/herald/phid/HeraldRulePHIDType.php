<?php

final class HeraldRulePHIDType extends PhorgePHIDType {

  const TYPECONST = 'HRUL';

  public function getTypeName() {
    return pht('Herald Rule');
  }

  public function newObject() {
    return new HeraldRule();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeHeraldApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new HeraldRuleQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $rule = $objects[$phid];

      $monogram = $rule->getMonogram();
      $name     = $rule->getName();

      $handle->setName($monogram);
      $handle->setFullName("{$monogram} {$name}");
      $handle->setURI("/{$monogram}");

      if ($rule->getIsDisabled()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^H\d*[1-9]\d*$/i', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new HeraldRuleQuery())
      ->setViewer($query->getViewer())
      ->withIDs(array_keys($id_map))
      ->execute();

    $results = array();
    foreach ($objects as $id => $object) {
      foreach (idx($id_map, $id, array()) as $name) {
        $results[$name] = $object;
      }
    }

    return $results;
  }

}
