<?php

final class PhabricatorAuthMessageTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorAuthMessageTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAuthApplication::class;
  }

}
