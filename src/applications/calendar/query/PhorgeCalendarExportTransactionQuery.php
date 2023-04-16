<?php

final class PhorgeCalendarExportTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeCalendarExportTransaction();
  }

}
