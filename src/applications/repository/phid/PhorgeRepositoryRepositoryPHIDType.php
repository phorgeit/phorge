<?php

final class PhorgeRepositoryRepositoryPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'REPO';

  public function getTypeName() {
    return pht('Repository');
  }

  public function getTypeIcon() {
    return 'fa-code';
  }

  public function newObject() {
    return new PhorgeRepository();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeRepositoryQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $repository = $objects[$phid];

      $monogram = $repository->getMonogram();
      $name = $repository->getName();
      $uri = $repository->getURI();

      $handle
        ->setName($monogram)
        ->setFullName("{$monogram} {$name}")
        ->setURI($uri)
        ->setMailStampName($monogram);

      if ($repository->getStatus() !== PhorgeRepository::STATUS_ACTIVE) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^(r[A-Z]+|R[1-9]\d*)\z/', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $results = array();
    $id_map = array();
    foreach ($names as $key => $name) {
      $id = substr($name, 1);
      $id_map[$id][] = $name;
      $names[$key] = substr($name, 1);
    }

    $query = id(new PhorgeRepositoryQuery())
      ->setViewer($query->getViewer())
      ->withIdentifiers($names);

    if ($query->execute()) {
      $objects = $query->getIdentifierMap();
      foreach ($objects as $key => $object) {
        foreach (idx($id_map, $key, array()) as $name) {
          $results[$name] = $object;
        }
      }
      return $results;
    } else {
      return array();
    }
  }

}
