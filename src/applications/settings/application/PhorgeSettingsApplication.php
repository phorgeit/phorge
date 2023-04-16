<?php

final class PhorgeSettingsApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/settings/';
  }

  public function getName() {
    return pht('Settings');
  }

  public function getShortDescription() {
    return pht('User Preferences');
  }

  public function getIcon() {
    return 'fa-wrench';
  }

  public function canUninstall() {
    return false;
  }

  public function getRoutes() {
    $panel_pattern = '(?:page/(?P<pageKey>[^/]+)/)?';

    return array(
      '/settings/' => array(
        $this->getQueryRoutePattern() => 'PhorgeSettingsListController',
        'user/(?P<username>[^/]+)/'.$panel_pattern
          => 'PhorgeSettingsMainController',
        'builtin/(?P<builtin>global)/'.$panel_pattern
          => 'PhorgeSettingsMainController',
        'panel/(?P<panel>[^/]+)/'
          => 'PhorgeSettingsMainController',
        'adjust/' => 'PhorgeSettingsAdjustController',
        'timezone/(?P<offset>[^/]+)/'
          => 'PhorgeSettingsTimezoneController',
        'issue/' => 'PhorgeSettingsIssueController',
      ),
    );
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

}
