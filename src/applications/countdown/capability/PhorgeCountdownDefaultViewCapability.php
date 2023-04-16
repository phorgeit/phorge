<?php

final class PhorgeCountdownDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'countdown.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
