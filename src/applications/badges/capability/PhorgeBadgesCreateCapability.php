<?php

final class PhorgeBadgesCreateCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'badges.default.create';

  public function getCapabilityName() {
    return pht('Can Create Badges');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create badges.');
  }

}
