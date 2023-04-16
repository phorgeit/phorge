<?php

final class PhorgePlatformSite extends PhorgeSite {

  public function getDescription() {
    return pht('Serves the core platform and applications.');
  }

  public function getPriority() {
    return 1000;
  }

  public function newSiteForRequest(AphrontRequest $request) {
    // If no base URI has been configured yet, match this site so the user
    // can follow setup instructions.
    $base_uri = PhorgeEnv::getEnvConfig('phorge.base-uri');
    if (!phutil_nonempty_string($base_uri)) {
      return new PhorgePlatformSite();
    }

    $uris = array();
    $uris[] = $base_uri;
    $uris[] = PhorgeEnv::getEnvConfig('phorge.production-uri');

    $allowed = PhorgeEnv::getEnvConfig('phorge.allowed-uris');
    if ($allowed) {
      foreach ($allowed as $uri) {
        $uris[] = $uri;
      }
    }

    $host = $request->getHost();
    if ($this->isHostMatch($host, $uris)) {
      return new PhorgePlatformSite();
    }

    return null;
  }

  public function getRoutingMaps() {
    $applications = PhorgeApplication::getAllInstalledApplications();

    $maps = array();
    foreach ($applications as $application) {
      $maps[] = $this->newRoutingMap()
        ->setApplication($application)
        ->setRoutes($application->getRoutes());
    }

    return $maps;
  }

  public function new404Controller(AphrontRequest $request) {
    return new PhorgePlatform404Controller();
  }

}
