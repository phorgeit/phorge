<?php

final class PhorgeCalendarImportEpochLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'epoch';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Out of Range');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht(
      'Ignored an event with an out-of-range date. Only dates between '.
      '1970 and 2037 are supported.');
  }

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-clock-o';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'red';
  }

}
