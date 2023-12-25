<?php

final class PhabricatorEditEngineConfigurationTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorEditEngineConfigurationTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorTransactionsApplication::class;
  }

}
