<?php

final class PhorgeFlagsApplication extends PhorgeApplication {

  public function getName() {
    return pht('Flags');
  }

  public function getShortDescription() {
    return pht('Personal Bookmarks');
  }

  public function getBaseURI() {
    return '/flag/';
  }

  public function getIcon() {
    return 'fa-flag';
  }

  public function getEventListeners() {
    return array(
      new PhorgeFlagsUIEventListener(),
    );
  }

  public function getTitleGlyph() {
    return "\xE2\x9A\x90";
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRoutes() {
    return array(
      '/flag/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?' => 'PhorgeFlagListController',
        'view/(?P<view>[^/]+)/' => 'PhorgeFlagListController',
        'edit/(?P<phid>[^/]+)/' => 'PhorgeFlagEditController',
        'delete/(?P<id>[1-9]\d*)/' => 'PhorgeFlagDeleteController',
      ),
    );
  }

}
