<?php

final class PhorgePackagesVersionTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgePackagesVersionTransaction();
  }

}
