<?php

final class PhabricatorPackagesVersionTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorPackagesVersionTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPackagesApplication::class;
  }

}
