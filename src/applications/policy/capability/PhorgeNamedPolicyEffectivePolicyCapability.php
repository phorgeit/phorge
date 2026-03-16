<?php

final class PhorgeNamedPolicyEffectivePolicyCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'policy.named.effective';

  public function getCapabilityName() {
    return pht('Effective Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
