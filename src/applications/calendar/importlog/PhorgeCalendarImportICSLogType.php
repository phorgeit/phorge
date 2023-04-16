<?php

final class PhorgeCalendarImportICSLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'ics';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('ICS Parse Error');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht(
      'Failed to parse ICS data ("%s"): %s',
      $log->getParameter('ics.code'),
      $log->getParameter('ics.message'));
  }


  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-file';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'red';
  }

}
