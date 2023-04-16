<?php

final class PhorgePackagesPublisherDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'packages.publisher.default.edit';

  public function getCapabilityName() {
    return pht('Default Publisher Edit Policy');
  }

}
