<?php

final class PhorgeCalendarImportTriggerLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'trigger';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Import Triggered');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {

    $via = $log->getParameter('via');
    switch ($via) {
      case PhorgeCalendarImportReloadWorker::VIA_BACKGROUND:
        return pht('Started background processing.');
      case PhorgeCalendarImportReloadWorker::VIA_TRIGGER:
      default:
        return pht('Triggered a periodic update.');
    }
  }

  public function getDisplayIcon(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'fa-clock-o';
  }

  public function getDisplayColor(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return 'blue';
  }

}
