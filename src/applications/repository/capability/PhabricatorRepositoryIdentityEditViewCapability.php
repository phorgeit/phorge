<?php

final class PhabricatorRepositoryIdentityEditViewCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'repository.identity.create';

  public function getCapabilityName() {
    return pht('Can Edit and View Identities');
  }

  public function describeCapabilityRejection() {
    return pht('You do not have permission to create or edit identities.');
  }

}
