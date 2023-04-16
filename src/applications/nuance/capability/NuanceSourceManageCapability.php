<?php

final class NuanceSourceManageCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'nuance.source.manage';

  public function getCapabilityName() {
    return pht('Can Manage Sources');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to manage sources.');
  }

}
