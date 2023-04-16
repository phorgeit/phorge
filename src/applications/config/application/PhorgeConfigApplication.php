<?php

final class PhorgeConfigApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/config/';
  }

  public function getIcon() {
    return 'fa-sliders';
  }

  public function isPinnedByDefault(PhorgeUser $viewer) {
    return $viewer->getIsAdmin();
  }

  public function getTitleGlyph() {
    return "\xE2\x9C\xA8";
  }

  public function getApplicationGroup() {
    return self::GROUP_ADMIN;
  }

  public function canUninstall() {
    return false;
  }

  public function getName() {
    return pht('Config');
  }

  public function getShortDescription() {
    return pht('Configure %s', PlatformSymbols::getPlatformServerName());
  }

  public function getRoutes() {
    return array(
      '/config/' => array(
        '' => 'PhorgeConfigConsoleController',
        'edit/(?P<key>[\w\.\-]+)/' => 'PhorgeConfigEditController',
        'database/'.
          '(?:(?P<ref>[^/]+)/'.
          '(?:(?P<database>[^/]+)/'.
          '(?:(?P<table>[^/]+)/'.
          '(?:(?:col/(?P<column>[^/]+)|key/(?P<key>[^/]+))/)?)?)?)?'
          => 'PhorgeConfigDatabaseStatusController',
        'dbissue/' => 'PhorgeConfigDatabaseIssueController',
        '(?P<verb>ignore|unignore)/(?P<key>[^/]+)/'
          => 'PhorgeConfigIgnoreController',
        'issue/' => array(
          '' => 'PhorgeConfigIssueListController',
          'panel/' => 'PhorgeConfigIssuePanelController',
          '(?P<key>[^/]+)/' => 'PhorgeConfigIssueViewController',
        ),
        'cache/' => array(
          '' => 'PhorgeConfigCacheController',
          'purge/' => 'PhorgeConfigPurgeCacheController',
        ),
        'module/' => array(
          '(?:(?P<module>[^/]+)/)?' => 'PhorgeConfigModuleController',
        ),
        'cluster/' => array(
          'databases/' => 'PhorgeConfigClusterDatabasesController',
          'notifications/' => 'PhorgeConfigClusterNotificationsController',
          'repositories/' => 'PhorgeConfigClusterRepositoriesController',
          'search/' => 'PhorgeConfigClusterSearchController',
        ),
        'settings/' => array(
          '' => 'PhorgeConfigSettingsListController',
          '(?P<filter>advanced|all)/'
            => 'PhorgeConfigSettingsListController',
          'history/' => 'PhorgeConfigSettingsHistoryController',
        ),
      ),
    );
  }

}
