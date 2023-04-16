<?php

final class PhorgeDoorkeeperApplication extends PhorgeApplication {

  public function canUninstall() {
    return false;
  }

  public function isLaunchable() {
    return false;
  }

  public function getName() {
    return pht('Doorkeeper');
  }

  public function getIcon() {
    return 'fa-recycle';
  }

  public function getShortDescription() {
    return pht('Connect to Other Software');
  }

  public function getRoutes() {
    return array(
      '/doorkeeper/' => array(
        'tags/' => 'DoorkeeperTagsController',
      ),
    );
  }

}
