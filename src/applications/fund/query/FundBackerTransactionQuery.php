<?php

final class FundBackerTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new FundBackerTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorFundApplication::class;
  }

}
