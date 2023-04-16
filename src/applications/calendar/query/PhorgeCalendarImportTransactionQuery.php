<?php

final class PhorgeCalendarImportTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeCalendarImportTransaction();
  }

}
