<?php

final class PhorgeResourceSite extends PhorgeSite {

  public function getDescription() {
    return pht('Serves static resources like images, CSS and JS.');
  }

  public function getPriority() {
    return 2000;
  }

  public function newSiteForRequest(AphrontRequest $request) {
    $host = $request->getHost();

    $uri = PhorgeEnv::getEnvConfig('security.alternate-file-domain');
    if (!phutil_nonempty_string($uri)) {
      return null;
    }

    if ($this->isHostMatch($host, array($uri))) {
      return new PhorgeResourceSite();
    }

    return null;
  }

  public function getRoutingMaps() {
    $applications = PhorgeApplication::getAllInstalledApplications();

    $maps = array();
    foreach ($applications as $application) {
      $maps[] = $this->newRoutingMap()
        ->setApplication($application)
        ->setRoutes($application->getResourceRoutes());
    }

    return $maps;
  }

}
