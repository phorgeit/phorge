<?php

final class PonderDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'ponder.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
