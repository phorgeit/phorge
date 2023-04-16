<?php

final class PhorgeAuthPasswordTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeAuthPasswordTransaction();
  }

}
