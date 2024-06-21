<?php

final class PhabricatorDashboardTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorDashboardTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorDashboardApplication::class;
  }

}
