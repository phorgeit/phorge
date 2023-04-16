<?php

final class PhorgeSpacesNamespacePHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'SPCE';

  public function getTypeName() {
    return pht('Space');
  }

  public function newObject() {
    return new PhorgeSpacesNamespace();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeSpacesApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeSpacesNamespaceQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $namespace = $objects[$phid];

      $monogram = $namespace->getMonogram();
      $name = $namespace->getNamespaceName();

      $handle
        ->setName($name)
        ->setFullName(pht('%s %s', $monogram, $name))
        ->setURI('/'.$monogram)
        ->setMailStampName($monogram);

      if ($namespace->getIsArchived()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^S[1-9]\d*$/i', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new PhorgeSpacesNamespaceQuery())
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
