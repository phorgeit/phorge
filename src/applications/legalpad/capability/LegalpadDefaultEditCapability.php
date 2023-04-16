<?php

final class LegalpadDefaultEditCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'legalpad.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
