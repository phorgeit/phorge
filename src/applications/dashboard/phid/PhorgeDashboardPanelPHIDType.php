<?php

final class PhorgeDashboardPanelPHIDType extends PhorgePHIDType {

  const TYPECONST = 'DSHP';

  public function getTypeName() {
    return pht('Panel');
  }

  public function newObject() {
    return new PhorgeDashboardPanel();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeDashboardPanelQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $panel = $objects[$phid];

      $name = $panel->getName();
      $monogram = $panel->getMonogram();

      $handle
        ->setIcon('fa-window-maximize')
        ->setName($name)
        ->setFullName("{$monogram} {$name}")
        ->setURI($panel->getURI());

      if ($panel->getIsArchived()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^W\d*[1-9]\d*$/i', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new PhorgeDashboardPanelQuery())
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
