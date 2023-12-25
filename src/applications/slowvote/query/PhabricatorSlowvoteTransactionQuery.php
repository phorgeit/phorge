<?php

final class PhabricatorSlowvoteTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorSlowvoteTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorSlowvoteApplication::class;
  }

}
