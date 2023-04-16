<?php

final class LegalpadTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new LegalpadTransaction();
  }

}
