<?php

abstract class PhorgeFulltextEngineExtension extends Phobject {

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  final protected function getViewer() {
    return PhorgeUser::getOmnipotentUser();
  }

  abstract public function getExtensionName();

  public function shouldEnrichFulltextObject($object) {
    return false;
  }

  public function enrichFulltextObject(
    $object,
    PhorgeSearchAbstractDocument $document) {
    return;
  }

  public function shouldIndexFulltextObject($object) {
    return false;
  }

  public function indexFulltextObject(
    $object,
    PhorgeSearchAbstractDocument $document) {
    return;
  }

  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->execute();
  }

  public function newFerretSearchFunctions() {
    return array();
  }

}
