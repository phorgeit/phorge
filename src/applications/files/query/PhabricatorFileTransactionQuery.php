<?php

final class PhabricatorFileTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorFileTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorFilesApplication::class;
  }

}
