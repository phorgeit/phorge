<?php

final class PhorgeRepositoryURITransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeRepositoryURITransaction();
  }

}
