<?php

final class PhortunePaymentMethodTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortunePaymentMethodTransaction();
  }

}
