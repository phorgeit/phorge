<?php

final class DiffusionDefaultEditCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'diffusion.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
