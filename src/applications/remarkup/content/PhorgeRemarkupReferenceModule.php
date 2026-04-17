<?php

abstract class PhorgeRemarkupReferenceModule {

  /** Also the URI! */
  abstract public function getModuleKey();

  abstract public function getTitle();

  abstract public function getModuleOrder();

  /**
   * For now, the returned string will be processed by the Remarkup processor.
   * Later, we might allow returning fancier things.
   *
   * @return string
   */
  abstract public function getContent();

  public static function getAllModules() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setUniqueMethod('getModuleKey')
      ->setSortMethod('getModuleOrder')
      ->execute();
  }

  /**
   * @return PhorgeRemarkupReferenceModule|null
   */
  public static function findModule($key) {
    return idx(self::getAllModules(), $key);
  }

}
