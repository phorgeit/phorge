<?php

final class PonderAnswerTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PonderAnswerTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPonderApplication::class;
  }

}
