<?php

final class PhorgeAuthFactorProviderTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeAuthFactorProviderTransaction();
  }

}
