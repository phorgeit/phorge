<?php

final class PhorgeCalendarEventTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeCalendarEventTransaction();
  }

}
