<?php

final class AlmanacServiceTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacServiceTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAlmanacApplication::class;
  }

}
