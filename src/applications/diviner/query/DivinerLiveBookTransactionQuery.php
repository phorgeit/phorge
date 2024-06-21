<?php

final class DivinerLiveBookTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new DivinerLiveBookTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorDivinerApplication::class;
  }

}
