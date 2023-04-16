<?php

final class PhorgeSystemApplication extends PhorgeApplication {

  public function getName() {
    return pht('System');
  }

  public function canUninstall() {
    return false;
  }

  public function isUnlisted() {
    return true;
  }

  public function getEventListeners() {
    return array(
      new PhorgeSystemDebugUIEventListener(),
    );
  }

  public function getRoutes() {
    return array(
      '/status/' => 'PhorgeStatusController',
      '/debug/' => 'PhorgeDebugController',
      '/favicon.ico' => 'PhorgeFaviconController',
      '/robots.txt' => 'PhorgeRobotsPlatformController',
      '/services/' => array(
        'encoding/' => 'PhorgeSystemSelectEncodingController',
        'highlight/' => 'PhorgeSystemSelectHighlightController',
        'viewas/' => 'PhorgeSystemSelectViewAsController',
      ),
      '/readonly/' => array(
        '(?P<reason>[^/]+)/' => 'PhorgeSystemReadOnlyController',
      ),
      '/object/(?P<name>[^/]+)/' => 'PhorgeSystemObjectController',
    );
  }

  public function getResourceRoutes() {
    return array(
      '/status/' => 'PhorgeStatusController',
      '/favicon.ico' => 'PhorgeFaviconController',
      '/robots.txt' => 'PhorgeRobotsResourceController',
    );
  }

}
