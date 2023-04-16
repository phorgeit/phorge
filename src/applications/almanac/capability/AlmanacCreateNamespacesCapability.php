<?php

final class AlmanacCreateNamespacesCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'almanac.namespaces';

  public function getCapabilityName() {
    return pht('Can Create Namespaces');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create Almanac namespaces.');
  }

}
