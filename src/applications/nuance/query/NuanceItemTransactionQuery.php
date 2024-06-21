<?php

final class NuanceItemTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new NuanceItemTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorNuanceApplication::class;
  }

}
