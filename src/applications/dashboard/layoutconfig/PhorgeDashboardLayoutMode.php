<?php

abstract class PhorgeDashboardLayoutMode
  extends Phobject {

  final public function getLayoutModeKey() {
    return $this->getPhobjectClassConstant('LAYOUTMODE', 32);
  }

  public function getLayoutModeOrder() {
    return 1000;
  }

  abstract public function getLayoutModeName();
  abstract public function getLayoutModeColumns();

  final protected function newColumn() {
    return new PhorgeDashboardColumn();
  }

  final public static function getAllLayoutModes() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getLayoutModeKey')
      ->setSortMethod('getLayoutModeOrder')
      ->execute();
  }

  final public static function getLayoutModeMap() {
    $modes = self::getAllLayoutModes();
    return mpull($modes, 'getLayoutModeName', 'getLayoutModeKey');
  }

}
