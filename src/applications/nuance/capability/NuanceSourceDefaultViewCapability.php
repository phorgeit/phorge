<?php

final class NuanceSourceDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'nuance.source.default.view';

  public function getCapabilityName() {
    return pht('Default Source View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
