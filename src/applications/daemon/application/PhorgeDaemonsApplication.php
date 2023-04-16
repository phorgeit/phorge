<?php

final class PhorgeDaemonsApplication extends PhorgeApplication {

  public function getName() {
    return pht('Daemons');
  }

  public function getShortDescription() {
    return pht('Manage Daemons');
  }

  public function getBaseURI() {
    return '/daemon/';
  }

  public function getTitleGlyph() {
    return "\xE2\x98\xAF";
  }

  public function getIcon() {
    return 'fa-pied-piper-alt';
  }

  public function getApplicationGroup() {
    return self::GROUP_ADMIN;
  }

  public function canUninstall() {
    return false;
  }

  public function getEventListeners() {
    return array(
      new PhorgeDaemonEventListener(),
    );
  }

  public function getRoutes() {
    return array(
      '/daemon/' => array(
        '' => 'PhorgeDaemonConsoleController',
        'task/(?P<id>[1-9]\d*)/' => 'PhorgeWorkerTaskDetailController',
        'log/' => array(
          '' => 'PhorgeDaemonLogListController',
          '(?P<id>[1-9]\d*)/' => 'PhorgeDaemonLogViewController',
        ),
        'bulk/' => array(
          '(?:query/(?P<queryKey>[^/]+)/)?' =>
            'PhorgeDaemonBulkJobListController',
          'monitor/(?P<id>\d+)/' =>
            'PhorgeDaemonBulkJobMonitorController',
          'view/(?P<id>\d+)/' =>
            'PhorgeDaemonBulkJobViewController',

        ),
      ),
    );
  }

}
