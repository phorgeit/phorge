<?php

final class PhorgePackagesPackageTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgePackagesPackageTransaction();
  }

}
