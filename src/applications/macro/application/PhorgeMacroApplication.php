<?php

final class PhorgeMacroApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/macro/';
  }

  public function getName() {
    return pht('Macro');
  }

  public function getShortDescription() {
    return pht('Image Macros and Memes');
  }

  public function getIcon() {
    return 'fa-file-image-o';
  }

  public function getTitleGlyph() {
    return "\xE2\x9A\x98";
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRoutes() {
    return array(
      '/macro/' => array(
        '(query/(?P<key>[^/]+)/)?' => 'PhorgeMacroListController',
        'create/' => 'PhorgeMacroEditController',
        'view/(?P<id>[1-9]\d*)/' => 'PhorgeMacroViewController',
        $this->getEditRoutePattern('edit/')
          => 'PhorgeMacroEditController',
        'audio/(?P<id>[1-9]\d*)/' => 'PhorgeMacroAudioController',
        'disable/(?P<id>[1-9]\d*)/' => 'PhorgeMacroDisableController',
        'meme/' => 'PhorgeMacroMemeController',
        'meme/create/' => 'PhorgeMacroMemeDialogController',
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeMacroManageCapability::CAPABILITY => array(
        'caption' => pht('Allows creating and editing macros.'),
      ),
    );
  }

  public function getMailCommandObjects() {
    return array(
      'macro' => array(
        'name' => pht('Email Commands: Macros'),
        'header' => pht('Interacting with Macros'),
        'object' => new PhorgeFileImageMacro(),
        'summary' => pht(
          'This page documents the commands you can use to interact with '.
          'image macros.'),
      ),
    );
  }

}
