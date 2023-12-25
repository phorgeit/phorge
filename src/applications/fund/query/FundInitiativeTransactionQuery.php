<?php

final class FundInitiativeTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new FundInitiativeTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorFundApplication::class;
  }

}
