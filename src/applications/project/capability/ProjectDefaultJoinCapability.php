<?php

final class ProjectDefaultJoinCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'project.default.join';

  public function getCapabilityName() {
    return pht('Default Join Policy');
  }

}
