<?php

final class PhorgeWorkerBulkJobTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeWorkerBulkJobTransaction();
  }

}
