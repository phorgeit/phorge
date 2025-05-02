<?php

final class PhabricatorPhurlURLDefaultViewCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'phurl.url.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
