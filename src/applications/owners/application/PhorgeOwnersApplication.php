<?php

final class PhorgeOwnersApplication extends PhorgeApplication {

  public function getName() {
    return pht('Owners');
  }

  public function getBaseURI() {
    return '/owners/';
  }

  public function getIcon() {
    return 'fa-gift';
  }

  public function getShortDescription() {
    return pht('Own Source Code');
  }

  public function getTitleGlyph() {
    return "\xE2\x98\x81";
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    return array(
      array(
        'name' => pht('Owners User Guide'),
        'href' => PhorgeEnv::getDoclink('Owners User Guide'),
      ),
    );
  }

  public function getFlavorText() {
    return pht('Adopt today!');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRemarkupRules() {
    return array(
      new PhorgeOwnersPackageRemarkupRule(),
    );
  }

  public function getRoutes() {
    return array(
      '/owners/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?' => 'PhorgeOwnersListController',
        'new/' => 'PhorgeOwnersEditController',
        'package/(?P<id>[1-9]\d*)/' => 'PhorgeOwnersDetailController',
        'archive/(?P<id>[1-9]\d*)/' => 'PhorgeOwnersArchiveController',
        'paths/(?P<id>[1-9]\d*)/' => 'PhorgeOwnersPathsController',

        $this->getEditRoutePattern('edit/')
          => 'PhorgeOwnersEditController',
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeOwnersDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for newly created packages.'),
        'template' => PhorgeOwnersPackagePHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_VIEW,
      ),
      PhorgeOwnersDefaultEditCapability::CAPABILITY => array(
        'caption' => pht('Default edit policy for newly created packages.'),
        'template' => PhorgeOwnersPackagePHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_EDIT,
      ),
    );
  }

}
