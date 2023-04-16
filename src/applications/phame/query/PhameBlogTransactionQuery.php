<?php

final class PhameBlogTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhameBlogTransaction();
  }

}
