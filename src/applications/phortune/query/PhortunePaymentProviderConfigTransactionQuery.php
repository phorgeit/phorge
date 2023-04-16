<?php

final class PhortunePaymentProviderConfigTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortunePaymentProviderConfigTransaction();
  }

}
