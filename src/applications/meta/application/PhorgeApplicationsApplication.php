<?php

final class PhorgeApplicationsApplication extends PhorgeApplication {

  public function getName() {
    return pht('Applications');
  }

  public function canUninstall() {
    return false;
  }

  public function isLaunchable() {
    // This application is launchable in the traditional sense, but showing it
    // on the application launch list is confusing.
    return false;
  }

  public function getBaseURI() {
    return '/applications/';
  }

  public function getShortDescription() {
    return pht('Explore More Applications');
  }

  public function getIcon() {
    return 'fa-globe';
  }

  public function getTitleGlyph() {
    return "\xE0\xBC\x84";
  }

  public function getRoutes() {
    return array(
      '/applications/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeApplicationsListController',
        'view/(?P<application>\w+)/'
          => 'PhorgeApplicationDetailViewController',
        'edit/(?P<application>\w+)/'
          => 'PhorgeApplicationEditController',
        'mailcommands/(?P<application>\w+)/(?P<type>\w+)/'
          => 'PhorgeApplicationEmailCommandsController',
        '(?P<application>\w+)/(?P<action>install|uninstall)/'
          => 'PhorgeApplicationUninstallController',
        'panel/(?P<application>\w+)/(?P<panel>\w+)/(?P<path>.*)'
          => 'PhorgeApplicationPanelController',
      ),
    );
  }

}
