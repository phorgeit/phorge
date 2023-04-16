<?php

final class PhorgeCalendarImportFrequencyLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'frequency';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Too Frequent');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {

    $frequency = $log->getParameter('frequency');

    return pht(
      'Ignored an event with an unsupported frequency rule ("%s"). Events '.
      'which repeat more frequently than daily are not supported.',
      $frequency);
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
