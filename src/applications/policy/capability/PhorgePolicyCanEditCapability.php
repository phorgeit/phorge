<?php

final class PhorgePolicyCanEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = self::CAN_EDIT;

  public function getCapabilityName() {
    return pht('Can Edit');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to edit this object.');
  }

}
