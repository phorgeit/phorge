<?php

final class HeraldRuleTransaction
  extends PhorgeModularTransaction {

  const TYPE_EDIT = 'herald:edit';

  public function getApplicationName() {
    return 'herald';
  }

  public function getApplicationTransactionType() {
    return HeraldRulePHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'HeraldRuleTransactionType';
  }

}
