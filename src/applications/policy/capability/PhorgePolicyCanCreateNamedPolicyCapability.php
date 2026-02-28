<?php

final class PhorgePolicyCanCreateNamedPolicyCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'policy.named.create';

  public function getCapabilityName() {
    return pht('Can Create Named Policies');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create named policies.');
  }

}
