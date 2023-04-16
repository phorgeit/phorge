<?php

final class ManiphestDefaultViewCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'maniphest.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
