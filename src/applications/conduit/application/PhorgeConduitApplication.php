<?php

final class PhorgeConduitApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/conduit/';
  }

  public function getIcon() {
    return 'fa-tty';
  }

  public function canUninstall() {
    return false;
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    return array(
      array(
        'name' => pht('Conduit API Overview'),
        'href' => PhorgeEnv::getDoclink('Conduit API Overview'),
      ),
    );
  }

  public function getName() {
    return pht('Conduit');
  }

  public function getShortDescription() {
    return pht('Developer API');
  }

  public function getTitleGlyph() {
    return "\xE2\x87\xB5";
  }

  public function getApplicationGroup() {
    return self::GROUP_DEVELOPER;
  }

  public function getApplicationOrder() {
    return 0.100;
  }

  public function getRoutes() {
    return array(
      '/conduit/' => array(
        $this->getQueryRoutePattern() => 'PhorgeConduitListController',
        'method/(?P<method>[^/]+)/' => 'PhorgeConduitConsoleController',
        'log/' => array(
          $this->getQueryRoutePattern() =>
            'PhorgeConduitLogController',
          'view/(?P<view>[^/]+)/' => 'PhorgeConduitLogController',
        ),
        'token/' => array(
          '' => 'PhorgeConduitTokenController',
          'edit/(?:(?P<id>\d+)/)?' =>
            'PhorgeConduitTokenEditController',
          'terminate/(?:(?P<id>\d+)/)?' =>
            'PhorgeConduitTokenTerminateController',
        ),
        'login/' => 'PhorgeConduitTokenHandshakeController',
      ),
      '/api/(?P<method>[^/]+)' => 'PhorgeConduitAPIController',
    );
  }

}
