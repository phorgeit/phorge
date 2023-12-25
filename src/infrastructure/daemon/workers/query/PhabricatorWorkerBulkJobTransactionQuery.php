<?php

final class PhabricatorWorkerBulkJobTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorWorkerBulkJobTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorDaemonsApplication::class;
  }

}
