<?php

final class ProjectDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'project.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
