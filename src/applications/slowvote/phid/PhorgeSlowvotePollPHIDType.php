<?php

final class PhorgeSlowvotePollPHIDType extends PhorgePHIDType {

  const TYPECONST = 'POLL';

  public function getTypeName() {
    return pht('Slowvote Poll');
  }

  public function newObject() {
    return new PhorgeSlowvotePoll();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeSlowvoteApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeSlowvoteQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $poll = $objects[$phid];

      $handle->setName('V'.$poll->getID());
      $handle->setFullName('V'.$poll->getID().': '.$poll->getQuestion());
      $handle->setURI('/V'.$poll->getID());
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^V\d*[1-9]\d*$/i', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new PhorgeSlowvoteQuery())
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
