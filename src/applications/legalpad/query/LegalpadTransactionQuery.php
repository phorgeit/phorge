<?php

final class LegalpadTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new LegalpadTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorLegalpadApplication::class;
  }

}
