<?php

final class HeraldRuleNameTransaction
  extends HeraldRuleTransactionType {

  use PhorgeNameTransactionTrait;

  const TRANSACTIONTYPE = 'herald:name';
}
