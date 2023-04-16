<?php

final class PhorgeSpacesCapabilityDefaultEdit
  extends PhorgePolicyCapability {

  const CAPABILITY = 'spaces.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
