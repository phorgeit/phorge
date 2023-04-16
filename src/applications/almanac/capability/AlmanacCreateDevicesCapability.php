<?php

final class AlmanacCreateDevicesCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'almanac.devices';

  public function getCapabilityName() {
    return pht('Can Create Devices');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create Almanac devices.');
  }

}
