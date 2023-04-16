<?php

final class ManiphestDefaultEditCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'maniphest.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
