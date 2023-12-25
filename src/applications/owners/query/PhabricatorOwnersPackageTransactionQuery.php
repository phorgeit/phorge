<?php

final class PhabricatorOwnersPackageTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorOwnersPackageTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorOwnersApplication::class;
  }

}
