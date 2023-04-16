<?php

final class PhorgePolicyCanJoinCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = self::CAN_JOIN;

  public function getCapabilityName() {
    return pht('Can Join');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to join this object.');
  }

}
