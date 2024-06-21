<?php

final class PhabricatorAuthFactorProviderTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorAuthFactorProviderTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAuthApplication::class;
  }

}
