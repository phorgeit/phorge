<?php

final class PassphraseDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'passphrase.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
