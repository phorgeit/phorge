<?php

final class PhorgeProjectColumnTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeProjectColumnTransaction();
  }

}
