<?php

final class PhamePostTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhamePostTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPhameApplication::class;
  }

}
