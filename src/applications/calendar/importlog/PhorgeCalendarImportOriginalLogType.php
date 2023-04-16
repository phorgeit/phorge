<?php

final class PhorgeCalendarImportOriginalLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'original';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Original Event');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {

    $phid = $log->getParameter('phid');

    return pht(
      'Ignored an event (%s) because the original version of this event '.
      'was created here.',
      $viewer->renderHandle($phid));
  }

}
