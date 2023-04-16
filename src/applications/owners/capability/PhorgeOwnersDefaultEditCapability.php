<?php

final class PhorgeOwnersDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'owners.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
