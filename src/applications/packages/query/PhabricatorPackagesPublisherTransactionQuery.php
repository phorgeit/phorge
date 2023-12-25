<?php

final class PhabricatorPackagesPublisherTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorPackagesPublisherTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPackagesApplication::class;
  }

}
