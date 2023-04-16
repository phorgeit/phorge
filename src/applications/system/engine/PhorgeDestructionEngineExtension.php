<?php

abstract class PhorgeDestructionEngineExtension extends Phobject {

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  abstract public function getExtensionName();

  public function canDestroyObject(
    PhorgeDestructionEngine $engine,
    $object) {
    return true;
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {
    return null;
  }

  public function didDestroyObject(
    PhorgeDestructionEngine $engine,
    $object) {
    return null;
  }

  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->execute();
  }

}
