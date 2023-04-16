<?php

final class PhamePostTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhamePostTransaction();
  }

}
