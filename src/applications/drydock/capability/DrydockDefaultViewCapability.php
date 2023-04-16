<?php

final class DrydockDefaultViewCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'drydock.default.view';

  public function getCapabilityName() {
    return pht('Default Blueprint View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
