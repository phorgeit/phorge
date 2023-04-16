<?php

final class PhorgeCountdownDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'countdown.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
