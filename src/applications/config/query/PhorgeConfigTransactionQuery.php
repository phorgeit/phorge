<?php

final class PhorgeConfigTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeConfigTransaction();
  }

}
