<?php

final class ProjectDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'project.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }
}
