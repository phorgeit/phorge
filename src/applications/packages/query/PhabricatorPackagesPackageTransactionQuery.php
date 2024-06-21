<?php

final class PhabricatorPackagesPackageTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorPackagesPackageTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPackagesApplication::class;
  }

}
