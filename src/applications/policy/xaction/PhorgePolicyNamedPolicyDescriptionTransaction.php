<?php

final class PhorgePolicyNamedPolicyDescriptionTransaction
  extends PhorgePolicyNamedPolicyTransactionType {

  const TRANSACTIONTYPE = 'namedpolicy:description';

  use PhorgeDescriptionTransactionTrait;

}
