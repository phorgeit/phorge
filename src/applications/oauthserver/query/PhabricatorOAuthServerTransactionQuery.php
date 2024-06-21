<?php

final class PhabricatorOAuthServerTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorOAuthServerTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorOAuthServerApplication::class;
  }

}
