<?php

final class PhabricatorCalendarImportTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorCalendarImportTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorCalendarApplication::class;
  }

}
