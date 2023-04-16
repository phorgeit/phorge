<?php

final class PhorgeCalendarImportEmptyLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'empty';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('No Events Imported');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Found no valid events to import.');
  }

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-ban';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'red';
  }

}
