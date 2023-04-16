<?php

final class PhorgeCalendarImportIgnoredNodeLogType
  extends PhorgeCalendarImportLogType {

  const LOGTYPE = 'nodetype';

  public function getDisplayType(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    return pht('Ignored Node');
  }

  public function getDisplayDescription(
    PhorgeUser $viewer,
    PhorgeCalendarImportLog $log) {
    $node_type = $log->getParameter('node.type');
    return pht(
      'Ignored unsupported "%s" node present in source.',
      $node_type);
  }

}
