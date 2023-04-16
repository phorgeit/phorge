<?php

final class PhorgeFileTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeFileTransaction();
  }

}
