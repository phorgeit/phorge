<?php

final class PhabricatorBadgesTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorBadgesTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorBadgesApplication::class;
  }

}
