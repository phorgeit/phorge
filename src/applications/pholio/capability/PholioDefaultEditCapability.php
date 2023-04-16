<?php

final class PholioDefaultEditCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'pholio.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
