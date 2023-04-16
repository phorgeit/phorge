<?php

final class PhortuneMerchantTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortuneMerchantTransaction();
  }

}
