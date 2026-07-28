<?php

final class PhorgeExtensionsApplication extends PhabricatorApplication {

  public function getBaseURI() {
    // For now, no GUI defined.
    return null;
  }

  public function getName() {
    return pht('Extensions');
  }

  public function getShortDescription() {
    return pht(
      'Manage %s Extensions',
      PlatformSymbols::getPlatformServerName());
  }

  public function getIcon() {
    return 'fa-battery-1';
  }

  public function isLaunchable() {
    return false;
  }

  public function canUninstall() {
    return false;
  }


  public function getApplicationGroup() {
    return self::GROUP_ADMIN;
  }

}
