<?php

final class PhorgePastePastePHIDType extends PhorgePHIDType {

  const TYPECONST = 'PSTE';

  public function getTypeName() {
    return pht('Paste');
  }

  public function newObject() {
    return new PhorgePaste();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePasteApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgePasteQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $paste = $objects[$phid];

      $id = $paste->getID();
      $name = $paste->getFullName();

      $handle->setName("P{$id}");
      $handle->setFullName($name);
      $handle->setURI("/P{$id}");
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^P\d*[1-9]\d*$/i', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new PhorgePasteQuery())
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
