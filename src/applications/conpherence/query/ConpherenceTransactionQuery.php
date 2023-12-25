<?php

final class ConpherenceTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new ConpherenceTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorConpherenceApplication::class;
  }

}
