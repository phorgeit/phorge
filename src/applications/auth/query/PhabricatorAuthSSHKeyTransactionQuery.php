<?php

final class PhabricatorAuthSSHKeyTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorAuthSSHKeyTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAuthApplication::class;
  }

}
