<?php

final class DivinerDefaultEditCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'diviner.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
