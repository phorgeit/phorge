<?php

abstract class PhorgeEditEngineExtension extends Phobject {

  private $viewer;

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  final public function setViewer($viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  final public function getViewer() {
    return $this->viewer;
  }

  public function getExtensionPriority() {
    return 1000;
  }

  abstract public function isExtensionEnabled();
  abstract public function getExtensionName();

  abstract public function supportsObject(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object);

  abstract public function buildCustomEditFields(
    PhorgeEditEngine $engine,
    PhorgeApplicationTransactionInterface $object);

  public function newBulkEditGroups(PhorgeEditEngine $engine) {
    return array();
  }

  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->setSortMethod('getExtensionPriority')
      ->execute();
  }

  final public static function getAllEnabledExtensions() {
    $extensions = self::getAllExtensions();

    foreach ($extensions as $key => $extension) {
      if (!$extension->isExtensionEnabled()) {
        unset($extensions[$key]);
      }
    }

    return $extensions;
  }

}
