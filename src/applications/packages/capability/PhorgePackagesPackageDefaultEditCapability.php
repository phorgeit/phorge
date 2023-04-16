<?php

final class PhorgePackagesPackageDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'packages.package.default.edit';

  public function getCapabilityName() {
    return pht('Default Package Edit Policy');
  }

}
