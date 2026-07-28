<?php

final class PhabricatorBadgesBadgeDescriptionTransaction
  extends PhabricatorBadgesBadgeTransactionType {

  use PhorgeDescriptionTransactionTrait;

  const TRANSACTIONTYPE = 'badge.description';

}
