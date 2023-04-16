<?php

final class PhorgeSlowvoteDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'slowvote.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
