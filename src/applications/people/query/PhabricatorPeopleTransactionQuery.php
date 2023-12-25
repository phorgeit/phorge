<?php

final class PhabricatorPeopleTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorUserTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPeopleApplication::class;
  }

}
