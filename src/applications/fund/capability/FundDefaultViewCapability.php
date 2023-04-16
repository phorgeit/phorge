<?php

final class FundDefaultViewCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'fund.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
