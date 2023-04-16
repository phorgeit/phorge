<?php

final class PhorgeCalendarImportFetchLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'fetch';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Fetched Calendar');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {

    return $viewer->renderHandle($log->getParameter('file.phid'));
  }

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-download';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'green';
  }

}
