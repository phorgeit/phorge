<?php

abstract class HeraldRuleTransactionType
  extends PhabricatorModularTransactionType {

  protected function renderObjectType() {
    return pht('rule');
  }

}
