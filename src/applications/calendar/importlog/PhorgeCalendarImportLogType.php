<?php

abstract class PhorgeCalendarImportLogType
  extends Phobject {

  final public function getLogTypeConstant() {
    return $this->getPhobjectClassConstant('LOGTYPE', 64);
  }

  final public static function getAllLogTypes() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getLogTypeConstant')
      ->execute();
  }

  abstract public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log);

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-warning';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'yellow';
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return null;
  }

}
