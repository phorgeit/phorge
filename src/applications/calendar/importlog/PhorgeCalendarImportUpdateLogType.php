<?php

final class PhorgeCalendarImportUpdateLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'update';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    $is_new = $log->getParameter('new');
    if ($is_new) {
      return pht('Imported Event');
    } else {
      return pht('Updated Event');
    }
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    $event_phid = $log->getParameter('phid');
    return $viewer->renderHandle($event_phid);
  }

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-calendar';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'green';
  }

}
