<?php

final class PhorgeRepositoryTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeRepositoryTransaction();
  }

}
