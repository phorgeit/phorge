<?php

final class PhorgeAuditApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/diffusion/commit/';
  }

  public function getIcon() {
    return 'fa-check-circle-o';
  }

  public function getName() {
    return pht('Audit');
  }

  public function getShortDescription() {
    return pht('Browse and Audit Commits');
  }

  public function canUninstall() {
    // Audit was once a separate application, but has largely merged with
    // Diffusion.
    return false;
  }

  public function isPinnedByDefault(PhorgeUser $viewer) {
    return parent::isClassInstalledForViewer(
      'PhorgeDiffusionApplication',
      $viewer);
  }

  public function getApplicationOrder() {
    return 0.130;
  }

}
