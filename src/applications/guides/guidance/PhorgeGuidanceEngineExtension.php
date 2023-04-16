<?php

abstract class PhorgeGuidanceEngineExtension
  extends Phobject {

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('GUIDANCEKEY', 64);
  }

  abstract public function canGenerateGuidance(
    PhorgeGuidanceContext $context);

  abstract public function generateGuidance(
    PhorgeGuidanceContext $context);

  public function didGenerateGuidance(
    PhorgeGuidanceContext $context,
    array $guidance) {
    return $guidance;
  }

  final protected function newGuidance($key) {
    return id(new PhorgeGuidanceMessage())
      ->setKey($key);
  }

  final protected function newWarning($key) {
    return $this->newGuidance($key)
      ->setSeverity(PhorgeGuidanceMessage::SEVERITY_WARNING);
  }

  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->execute();
  }

}
