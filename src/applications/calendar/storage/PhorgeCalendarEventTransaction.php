<?php

final class PhorgeCalendarEventTransaction
  extends PhorgeModularTransaction {

  const MAILTAG_RESCHEDULE = 'calendar-reschedule';
  const MAILTAG_CONTENT = 'calendar-content';
  const MAILTAG_OTHER = 'calendar-other';

  public function getApplicationName() {
    return 'calendar';
  }

  public function getApplicationTransactionType() {
    return PhorgeCalendarEventPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeCalendarEventTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgeCalendarEventTransactionType';
  }

  public function getMailTags() {
    $tags = array();
    switch ($this->getTransactionType()) {
      case PhorgeCalendarEventNameTransaction::TRANSACTIONTYPE:
      case PhorgeCalendarEventDescriptionTransaction::TRANSACTIONTYPE:
      case PhorgeCalendarEventInviteTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_CONTENT;
        break;
      case PhorgeCalendarEventStartDateTransaction::TRANSACTIONTYPE:
      case PhorgeCalendarEventEndDateTransaction::TRANSACTIONTYPE:
      case PhorgeCalendarEventCancelTransaction::TRANSACTIONTYPE:
        $tags[] = self::MAILTAG_RESCHEDULE;
        break;
    }
    return $tags;
  }

}
