<?php

final class AlmanacBindingTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacBindingTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAlmanacApplication::class;
  }

}
