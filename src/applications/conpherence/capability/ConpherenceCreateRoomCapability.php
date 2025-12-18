<?php

final class ConpherenceCreateRoomCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'conpherence.create';

  public function getCapabilityName() {
    return pht('Can Create Rooms');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create new rooms.');
  }

}
