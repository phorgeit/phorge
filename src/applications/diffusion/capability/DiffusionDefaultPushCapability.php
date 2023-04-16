<?php

final class DiffusionDefaultPushCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'diffusion.default.push';

  public function getCapabilityName() {
    return pht('Default Push Policy');
  }

}
