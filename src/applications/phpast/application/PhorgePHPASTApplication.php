<?php

final class PhorgePHPASTApplication extends PhorgeApplication {

  public function getName() {
    return pht('PHPAST');
  }

  public function getBaseURI() {
    return '/xhpast/';
  }

  public function getIcon() {
    return 'fa-ambulance';
  }

  public function getShortDescription() {
    return pht('Visual PHP Parser');
  }

  public function getTitleGlyph() {
    return "\xE2\x96\xA0";
  }

  public function getApplicationGroup() {
    return self::GROUP_DEVELOPER;
  }

  public function getRoutes() {
    return array(
      '/xhpast/' => array(
        '' => 'PhorgeXHPASTViewRunController',
        'view/(?P<id>[1-9]\d*)/'
          => 'PhorgeXHPASTViewFrameController',
        'frameset/(?P<id>[1-9]\d*)/'
          => 'PhorgeXHPASTViewFramesetController',
        'input/(?P<id>[1-9]\d*)/'
          => 'PhorgeXHPASTViewInputController',
        'tree/(?P<id>[1-9]\d*)/'
          => 'PhorgeXHPASTViewTreeController',
        'stream/(?P<id>[1-9]\d*)/'
          => 'PhorgeXHPASTViewStreamController',
      ),
    );
  }

}
