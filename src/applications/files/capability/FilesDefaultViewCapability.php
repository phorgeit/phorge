<?php

final class FilesDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'files.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
