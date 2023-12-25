<?php

final class PhabricatorRepositoryTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorRepositoryTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorDiffusionApplication::class;
  }

}
