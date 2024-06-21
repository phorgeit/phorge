<?php

final class PhabricatorAuthProviderConfigTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorAuthProviderConfigTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAuthApplication::class;
  }

}
