<?php

final class PhabricatorConfigTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorConfigTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorConfigApplication::class;
  }

}
