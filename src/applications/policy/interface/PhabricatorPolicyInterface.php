<?php

interface PhabricatorPolicyInterface extends PhabricatorPHIDInterface {

  /**
   * @return array<PhabricatorPolicyCapability>
   */
  public function getCapabilities();

  /**
   * @param PhabricatorPolicyCapability $capability
   * @return string A PhabricatorPolicyConstant
   */
  public function getPolicy($capability);

  /**
   * Whether an object provides automatic capability grants to a user (e.g. the
   * owner of an object can always see it even if a capability is set to NOONE)
   *
   * @param PhabricatorPolicyCapability $capability
   * @param PhabricatorUser $viewer
   * @return bool
   */
  public function hasAutomaticCapability($capability, PhabricatorUser $viewer);

}

// TEMPLATE IMPLEMENTATION /////////////////////////////////////////////////////

/* -(  PhabricatorPolicyInterface  )----------------------------------------- */
/*

  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        return PhabricatorPolicies::POLICY_USER;
    }
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return false;
  }

*/
