<?php

final class PhabricatorCountdownCreateCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'countdown.create';

  public function getCapabilityName() {
    return pht('Can Create Countdowns');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create a countdown.');
  }

}
