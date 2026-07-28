<?php

final class PhabricatorBadgesBadgeNameTransaction
  extends PhabricatorBadgesBadgeTransactionType {

  use PhorgeNameTransactionTrait;

  const TRANSACTIONTYPE = 'badge.name';

}
