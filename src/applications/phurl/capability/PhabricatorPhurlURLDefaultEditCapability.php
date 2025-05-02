<?php

final class PhabricatorPhurlURLDefaultEditCapability
  extends PhabricatorPolicyCapability {

  const CAPABILITY = 'phurl.url.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
