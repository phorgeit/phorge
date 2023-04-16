<?php

final class PhorgeFileFilePHIDType extends PhorgePHIDType {

  const TYPECONST = 'FILE';

  public function getTypeName() {
    return pht('File');
  }

  public function newObject() {
    return new PhorgeFile();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeFilesApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeFileQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $file = $objects[$phid];

      $id = $file->getID();
      $name = $file->getName();
      $uri = $file->getInfoURI();

      $handle->setName("F{$id}");
      $handle->setFullName("F{$id}: {$name}");
      $handle->setURI($uri);

      $icon = FileTypeIcon::getFileIcon($name);
      $handle->setIcon($icon);
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^F\d*[1-9]\d*$/', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = (int)substr($name, 1);
      $id_map[$id][] = $name;
    }

    $objects = id(new PhorgeFileQuery())
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
