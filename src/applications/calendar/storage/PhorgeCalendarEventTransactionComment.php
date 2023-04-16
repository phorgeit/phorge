<?php

final class PhorgeCalendarEventTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgeCalendarEventTransaction();
  }

}
