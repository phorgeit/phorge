<?php

final class PhorgePackagesPackageDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'packages.package.default.view';

  public function getCapabilityName() {
    return pht('Default Package View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
