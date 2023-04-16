<?php

final class PhorgeCalendarEventDeclineTransaction
  extends PhorgeCalendarEventReplyTransaction {

  const TRANSACTIONTYPE = 'calendar.decline';

  public function generateNewValue($object, $value) {
    return PhorgeCalendarEventInvitee::STATUS_DECLINED;
  }

  public function getTitle() {
    return pht(
      '%s declined this event.',
      $this->renderAuthor());
  }

  public function getTitleForFeed() {
    return pht(
      '%s declined %s.',
      $this->renderAuthor(),
      $this->renderObject());
  }

}
