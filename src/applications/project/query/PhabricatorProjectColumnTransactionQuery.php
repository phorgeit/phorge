<?php

final class PhabricatorProjectColumnTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorProjectColumnTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorProjectApplication::class;
  }

}
