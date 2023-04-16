<?php

final class PhorgeOAuthServerTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeOAuthServerTransaction();
  }

}
