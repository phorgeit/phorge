<?php

interface PhorgePolicyInterface extends PhorgePHIDInterface {

  public function getCapabilities();
  public function getPolicy($capability);
  public function hasAutomaticCapability($capability, PhorgeUser $viewer);

}

// TEMPLATE IMPLEMENTATION /////////////////////////////////////////////////////

/* -(  PhorgePolicyInterface  )----------------------------------------- */
/*

  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::POLICY_USER;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

*/
