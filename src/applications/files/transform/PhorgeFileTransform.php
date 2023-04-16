<?php

abstract class PhorgeFileTransform extends Phobject {

  abstract public function getTransformName();
  abstract public function getTransformKey();
  abstract public function canApplyTransform(PhorgeFile $file);
  abstract public function applyTransform(PhorgeFile $file);

  public function getDefaultTransform(PhorgeFile $file) {
    return null;
  }

  public function generateTransforms() {
    return array($this);
  }

  public function executeTransform(PhorgeFile $file) {
    if ($this->canApplyTransform($file)) {
      try {
        return $this->applyTransform($file);
      } catch (Exception $ex) {
        // Ignore.
      }
    }

    return $this->getDefaultTransform($file);
  }

  public static function getAllTransforms() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setExpandMethod('generateTransforms')
      ->setUniqueMethod('getTransformKey')
      ->execute();
  }

  public static function getTransformByKey($key) {
    $all = self::getAllTransforms();

    $xform = idx($all, $key);
    if (!$xform) {
      throw new Exception(
        pht(
          'No file transform with key "%s" exists.',
          $key));
    }

    return $xform;
  }

}
