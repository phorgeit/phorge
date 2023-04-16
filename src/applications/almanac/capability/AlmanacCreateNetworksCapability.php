<?php

final class AlmanacCreateNetworksCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'almanac.networks';

  public function getCapabilityName() {
    return pht('Can Create Networks');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create Almanac networks.');
  }

}
