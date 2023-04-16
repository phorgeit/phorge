<?php

final class PholioTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PholioTransaction();
  }

}
