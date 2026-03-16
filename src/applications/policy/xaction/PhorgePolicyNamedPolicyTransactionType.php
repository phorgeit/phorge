<?php

abstract class PhorgePolicyNamedPolicyTransactionType
  extends PhabricatorModularTransactionType {

  protected function renderObjectType() {
    return 'named policy';
  }

}
