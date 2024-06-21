<?php

final class PhabricatorProjectTriggerTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorProjectTriggerTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorProjectApplication::class;
  }

}
