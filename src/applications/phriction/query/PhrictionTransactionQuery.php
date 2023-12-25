<?php

final class PhrictionTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhrictionTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPhrictionApplication::class;
  }

}
