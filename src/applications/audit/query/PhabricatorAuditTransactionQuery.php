<?php

final class PhabricatorAuditTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorAuditTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAuditApplication::class;
  }

}
