<?php

final class PhorgeBadgesDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'badges.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Badges');
  }

}
