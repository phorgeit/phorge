<?php

final class PhorgeXHProfApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/xhprof/';
  }

  public function getName() {
    return pht('XHProf');
  }

  public function getShortDescription() {
    return pht('PHP Profiling Tool');
  }

  public function getIcon() {
    return 'fa-stethoscope';
  }

  public function getTitleGlyph() {
    return "\xE2\x98\x84";
  }

  public function getApplicationGroup() {
    return self::GROUP_DEVELOPER;
  }

  public function getRoutes() {
    return array(
      '/xhprof/' => array(
        '' => 'PhorgeXHProfSampleListController',
        'list/(?P<view>[^/]+)/' => 'PhorgeXHProfSampleListController',
        'profile/(?P<phid>[^/]+)/' => 'PhorgeXHProfProfileController',
        'import/drop/' => 'PhorgeXHProfDropController',
      ),
    );
  }

}
