<?php

final class PasteDefaultEditCapability extends PhorgePolicyCapability {

  const CAPABILITY = 'paste.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
