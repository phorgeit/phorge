<?php

final class PassphraseDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'passphrase.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
