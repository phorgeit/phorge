<?php

final class PhorgeUIExamplesApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/uiexample/';
  }

  public function getShortDescription() {
    return pht('Developer UI Examples');
  }

  public function getName() {
    return pht('UIExamples');
  }

  public function getIcon() {
    return 'fa-magnet';
  }

  public function getTitleGlyph() {
    return "\xE2\x8F\x9A";
  }

  public function getFlavorText() {
    return pht('A gallery of modern art.');
  }

  public function getApplicationGroup() {
    return self::GROUP_DEVELOPER;
  }

  public function isPrototype() {
    return true;
  }

  public function getApplicationOrder() {
    return 0.110;
  }

  public function getRoutes() {
    return array(
      '/uiexample/' => array(
        '' => 'PhorgeUIExampleRenderController',
        'view/(?P<class>[^/]+)/' => 'PhorgeUIExampleRenderController',
      ),
    );
  }

}
