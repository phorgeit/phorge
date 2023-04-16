<?php

final class PhorgeRepositoryIdentityTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeRepositoryIdentityTransaction();
  }

}
