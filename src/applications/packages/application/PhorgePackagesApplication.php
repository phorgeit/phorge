<?php

final class PhorgePackagesApplication extends PhorgeApplication {

  public function getName() {
    return pht('Packages');
  }

  public function getShortDescription() {
    return pht('Publish Software');
  }

  public function getFlavorText() {
    return pht('Applications and Extensions');
  }

  public function getBaseURI() {
    return '/packages/package/';
  }

  public function getIcon() {
    return 'fa-gift';
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function isPrototype() {
    return true;
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgePackagesCreatePublisherCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
      ),
      PhorgePackagesPublisherDefaultEditCapability::CAPABILITY => array(
        'caption' => pht('Default edit policy for newly created publishers.'),
        'template' => PhorgePackagesPublisherPHIDType::TYPECONST,
        'default' => PhorgePolicies::POLICY_NOONE,
      ),
      PhorgePackagesPackageDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default edit policy for newly created packages.'),
        'template' => PhorgePackagesPackagePHIDType::TYPECONST,
      ),
      PhorgePackagesPackageDefaultEditCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for newly created packages.'),
        'template' => PhorgePackagesPackagePHIDType::TYPECONST,
        'default' => PhorgePolicies::POLICY_NOONE,
      ),
    );
  }
  public function getRoutes() {
    return array(
      '/package/' => array(
        '(?P<publisherKey>[^/]+)/' => array(
          '' => 'PhorgePackagesPublisherViewController',
          '(?P<packageKey>[^/]+)/' => array(
            '' => 'PhorgePackagesPackageViewController',
            '(?P<versionKey>[^/]+)/' =>
              'PhorgePackagesVersionViewController',
          ),
        ),
      ),
      '/packages/' => array(
        'publisher/' => array(
          $this->getQueryRoutePattern() =>
            'PhorgePackagesPublisherListController',
          $this->getEditRoutePattern('edit/') =>
            'PhorgePackagesPublisherEditController',
        ),
        'package/' => array(
          $this->getQueryRoutePattern() =>
            'PhorgePackagesPackageListController',
          $this->getEditRoutePattern('edit/') =>
            'PhorgePackagesPackageEditController',
        ),
        'version/' => array(
          $this->getQueryRoutePattern() =>
            'PhorgePackagesVersionListController',
          $this->getEditRoutePattern('edit/') =>
            'PhorgePackagesVersionEditController',
        ),
      ),
    );
  }

}
