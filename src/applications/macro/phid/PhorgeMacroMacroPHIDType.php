<?php

final class PhorgeMacroMacroPHIDType extends PhorgePHIDType {

  const TYPECONST = 'MCRO';

  public function getTypeName() {
    return pht('Image Macro');
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeMacroApplication';
  }

  public function getTypeIcon() {
    return 'fa-meh-o';
  }

  public function newObject() {
    return new PhorgeFileImageMacro();
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeMacroQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $macro = $objects[$phid];

      $id = $macro->getID();
      $name = $macro->getName();

      $handle->setName($name);
      $handle->setFullName(pht('Image Macro "%s"', $name));
      $handle->setURI("/macro/view/{$id}/");
    }
  }

}
