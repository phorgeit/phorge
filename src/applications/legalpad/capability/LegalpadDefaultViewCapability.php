<?php

final class LegalpadDefaultViewCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'legalpad.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
