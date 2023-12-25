<?php

final class PonderQuestionTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PonderQuestionTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPonderApplication::class;
  }

}
