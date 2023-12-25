<?php

final class PhluxTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhluxTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPhluxApplication::class;
  }

}
