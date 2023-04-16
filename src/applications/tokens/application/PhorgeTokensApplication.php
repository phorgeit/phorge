<?php

final class PhorgeTokensApplication extends PhorgeApplication {

  public function getName() {
    return pht('Tokens');
  }

  public function getBaseURI() {
    return '/token/';
  }

  public function getIcon() {
    return 'fa-thumbs-up';
  }

  public function getTitleGlyph() {
    return "\xE2\x99\xA6";
  }

  public function getShortDescription() {
    return pht('Award and Acquire Trinkets');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRoutes() {
    return array(
      '/token/' => array(
        '' => 'PhorgeTokenGivenController',
        'given/' => 'PhorgeTokenGivenController',
        'give/(?<phid>[^/]+)/' => 'PhorgeTokenGiveController',
        'leaders/' => 'PhorgeTokenLeaderController',
      ),
    );
  }

  public function getEventListeners() {
    return array(
      new PhorgeTokenUIEventListener(),
    );
  }

}
