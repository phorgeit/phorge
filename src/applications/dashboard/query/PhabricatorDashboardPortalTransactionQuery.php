<?php

final class PhabricatorDashboardPortalTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorDashboardPortalTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorDashboardApplication::class;
  }

}
