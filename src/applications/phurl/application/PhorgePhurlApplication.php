<?php

final class PhorgePhurlApplication extends PhorgeApplication {

  public function getName() {
    return pht('Phurl');
  }

  public function getShortDescription() {
    return pht('URL Shortener');
  }

  public function getFlavorText() {
    return pht('Shorten your favorite URL.');
  }

  public function getBaseURI() {
    return '/phurl/';
  }

  public function getIcon() {
    return 'fa-compress';
  }

  public function isPrototype() {
    return true;
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRemarkupRules() {
    return array(
      new PhorgePhurlRemarkupRule(),
      new PhorgePhurlLinkRemarkupRule(),
    );
  }

  public function getRoutes() {
    return array(
      '/U(?P<id>[1-9]\d*)/?' => 'PhorgePhurlURLViewController',
      '/u/(?P<id>[1-9]\d*)/?' => 'PhorgePhurlURLAccessController',
      '/u/(?P<alias>[^/]+)/?' => 'PhorgePhurlURLAccessController',
      '/phurl/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgePhurlURLListController',
        'url/' => array(
          $this->getEditRoutePattern('edit/')
            => 'PhorgePhurlURLEditController',
        ),
      ),
    );
  }

  public function getShortRoutes() {
    return array(
      '/status/' => 'PhorgeStatusController',
      '/favicon.ico' => 'PhorgeFaviconController',
      '/robots.txt' => 'PhorgeRobotsShortController',

      '/u/(?P<append>[^/]+)' => 'PhorgePhurlShortURLController',
      '.*' => 'PhorgePhurlShortURLDefaultController',
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgePhurlURLCreateCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_USER,
      ),
    );
  }

}
