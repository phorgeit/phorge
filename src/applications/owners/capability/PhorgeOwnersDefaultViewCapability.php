<?php

final class PhorgeOwnersDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'owners.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
