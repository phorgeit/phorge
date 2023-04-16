<?php

final class PhorgeCalendarImportICSWarningLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'ics.warning';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('ICS Parser Warning');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht(
      'Warning ("%s") while parsing ICS data (near line %s): %s',
      $log->getParameter('ics.warning.code'),
      $log->getParameter('ics.warning.line'),
      $log->getParameter('ics.warning.message'));
  }


  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-exclamation-triangle';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'yellow';
  }

}
