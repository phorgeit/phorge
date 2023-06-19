<?php

final class PhabricatorDashboardCreateCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'dashboard.create';

  public function getCapabilityName() {
    return pht('Can Create Dashboards');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create a dashboard.');
  }

}
