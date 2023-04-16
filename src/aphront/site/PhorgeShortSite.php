<?php

final class PhorgeShortSite extends PhorgeSite {

  public function getDescription() {
    return pht('Serves shortened URLs.');
  }

  public function getPriority() {
    return 2500;
  }

  public function newSiteForRequest(AphrontRequest $request) {
    $host = $request->getHost();

    $uri = PhorgeEnv::getEnvConfig('phurl.short-uri');
    if (!phutil_nonempty_string($uri)) {
      return null;
    }

    $phurl_installed = PhorgeApplication::isClassInstalled(
      'PhorgePhurlApplication');
    if (!$phurl_installed) {
      return false;
    }

    if ($this->isHostMatch($host, array($uri))) {
      return new PhorgeShortSite();
    }

    return null;
  }

  public function getRoutingMaps() {
    $app = PhorgeApplication::getByClass('PhorgePhurlApplication');

    $maps = array();
    $maps[] = $this->newRoutingMap()
      ->setApplication($app)
      ->setRoutes($app->getShortRoutes());
    return $maps;
  }

}
