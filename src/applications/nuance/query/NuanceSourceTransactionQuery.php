<?php

final class NuanceSourceTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new NuanceSourceTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorNuanceApplication::class;
  }

}
