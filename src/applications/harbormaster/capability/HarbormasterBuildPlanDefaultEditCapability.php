<?php

final class HarbormasterBuildPlanDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'harbormaster.plan.default.edit';

  public function getCapabilityName() {
    return pht('Default Build Plan Edit Policy');
  }

}
