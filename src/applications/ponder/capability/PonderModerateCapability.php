<?php

final class PonderModerateCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'ponder.moderate';

  public function getCapabilityName() {
    return pht('Moderate Policy');
  }

}
