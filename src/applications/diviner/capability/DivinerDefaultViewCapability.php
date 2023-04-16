<?php

final class DivinerDefaultViewCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'diviner.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
