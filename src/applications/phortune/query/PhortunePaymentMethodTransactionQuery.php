<?php

final class PhortunePaymentMethodTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortunePaymentMethodTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPhortuneApplication::class;
  }

}
