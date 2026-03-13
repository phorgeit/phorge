<?php

final class PhabricatorCalendarEventNameTransaction
  extends PhabricatorCalendarEventTransactionType {

  use PhorgeNameTransactionTrait;

  const TRANSACTIONTYPE = 'calendar.name';
}
