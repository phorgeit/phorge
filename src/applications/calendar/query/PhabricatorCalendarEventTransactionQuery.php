<?php

final class PhabricatorCalendarEventTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorCalendarEventTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorCalendarApplication::class;
  }

}
