<?php

final class PhorgeAuditTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeAuditTransaction();
  }

}
