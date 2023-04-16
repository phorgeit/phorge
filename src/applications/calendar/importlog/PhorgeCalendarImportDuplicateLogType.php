<?php

final class PhorgeCalendarImportDuplicateLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'duplicate';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Duplicate Event');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    $duplicate_uid = $log->getParameter('uid.full');
    return pht(
      'Ignored duplicate event "%s" present in source.',
      $duplicate_uid);
  }

}
