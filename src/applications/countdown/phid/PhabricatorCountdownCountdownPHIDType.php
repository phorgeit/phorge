<?php

final class PhabricatorCountdownCountdownPHIDType extends PhabricatorPHIDType {

  const TYPECONST = 'CDWN';

  public function getTypeName() {
    return pht('Countdown');
  }

  public function newObject() {
    return new PhabricatorCountdown();
  }

  public function getPHIDTypeApplicationClass() {
    return PhabricatorCountdownApplication::class;
  }

  protected function buildQueryForObjects(
    PhabricatorObjectQuery $query,
    array $phids) {

    return id(new PhabricatorCountdownQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhabricatorHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $countdown = $objects[$phid];

      $name = $countdown->getTitle();
      $id = $countdown->getID();

      $handle->setName($countdown->getMonogram());
      $handle->setFullName(pht('%s: %s', $countdown->getMonogram(), $name));
      $handle->setURI($countdown->getURI());
    }
  }

  /**
   * Check whether a named object is of this PHID type
   * @param string $name Object name
   * @return bool True if the named object is of this PHID type
   */
  public function canLoadNamedObject($name) {
    return preg_match('/^C\d*[1-9]\d*$/i', $name);
  }

  public function loadNamedObjects(
    PhabricatorObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new PhabricatorCountdownQuery())
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
