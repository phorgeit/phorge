<?php

final class PhorgeAuthProviderConfigTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeAuthProviderConfigTransaction();
  }

}
