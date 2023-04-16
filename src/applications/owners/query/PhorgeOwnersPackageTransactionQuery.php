<?php

final class PhorgeOwnersPackageTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeOwnersPackageTransaction();
  }

}
