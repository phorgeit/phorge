<?php

final class NuanceQueueTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new NuanceQueueTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorNuanceApplication::class;
  }

}
