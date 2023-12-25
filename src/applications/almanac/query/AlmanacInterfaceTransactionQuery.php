<?php

final class AlmanacInterfaceTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacInterfaceTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAlmanacApplication::class;
  }

}
