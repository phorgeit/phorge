<?php

final class PhabricatorCalendarEventDescriptionTransaction
  extends PhabricatorCalendarEventTransactionType {

  use PhorgeDescriptionTransactionTrait;

  const TRANSACTIONTYPE = 'calendar.description';

}
