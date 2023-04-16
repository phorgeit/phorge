<?php

final class PhorgeCalendarImportOrphanLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'orphan';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Orphan');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    $child_uid = $log->getParameter('uid.full');
    $parent_uid = $log->getParameter('uid.parent');
    return pht(
      'Found orphaned child event ("%s") without a parent event ("%s").',
      $child_uid,
      $parent_uid);
  }

}
