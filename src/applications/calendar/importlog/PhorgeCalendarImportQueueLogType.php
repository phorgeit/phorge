<?php

final class PhorgeCalendarImportQueueLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'queue';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Queued');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {

    $size = $log->getParameter('data.size');
    $limit = $log->getParameter('data.limit');

    return pht(
      'Queued for background import: data size (%s) exceeds limit for '.
      'immediate processing (%s).',
      phutil_format_bytes($size),
      phutil_format_bytes($limit));
  }

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-sort-amount-desc';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'blue';
  }

}
