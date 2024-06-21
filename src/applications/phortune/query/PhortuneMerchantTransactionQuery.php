<?php

final class PhortuneMerchantTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortuneMerchantTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPhortuneApplication::class;
  }

}
