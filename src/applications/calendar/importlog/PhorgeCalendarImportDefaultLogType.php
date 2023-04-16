<?php

final class PhorgeCalendarImportDefaultLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'default';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {

    $type = $log->getParameter('type');
    if (strlen($type)) {
      return pht('Unknown Message "%s"', $type);
    } else {
      return pht('Unknown Message');
    }
  }

}
