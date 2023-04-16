<?php

final class PhorgeCalendarImportDeleteLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'delete';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Deleted Event');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht(
      'Deleted event "%s" which is no longer present in the source.',
      $log->getParameter('name'));
  }

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-times';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'grey';
  }

}
