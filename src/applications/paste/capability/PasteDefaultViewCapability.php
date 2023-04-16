<?php

final class PasteDefaultViewCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'paste.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
