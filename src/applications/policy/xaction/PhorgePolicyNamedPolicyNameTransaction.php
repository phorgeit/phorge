<?php

final class PhorgePolicyNamedPolicyNameTransaction
  extends PhorgePolicyNamedPolicyTransactionType {

  use PhorgeNameTransactionTrait;

  const TRANSACTIONTYPE = 'namedpolicy:name';

}
