<?php

final class DifferentialDiffTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new DifferentialDiffTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorDifferentialApplication::class;
  }

}
