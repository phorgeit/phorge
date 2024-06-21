<?php

final class DifferentialTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new DifferentialTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorDifferentialApplication::class;
  }

}
