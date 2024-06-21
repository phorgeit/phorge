<?php

final class AlmanacDeviceTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacDeviceTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorAlmanacApplication::class;
  }

}
