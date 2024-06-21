<?php

final class HeraldTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HeraldRuleTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorHeraldApplication::class;
  }

}
