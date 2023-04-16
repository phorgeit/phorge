<?php

final class NuanceSourceDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'nuance.source.default.edit';

  public function getCapabilityName() {
    return pht('Default Source Edit Policy');
  }

}
