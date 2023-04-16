<?php

final class PhorgeApplicationApplicationTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeApplicationApplicationTransaction();
  }

}
