<?php

final class PhabricatorMetaMTAApplicationEmailTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorMetaMTAApplicationEmailTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorMetaMTAApplication::class;
  }

}
