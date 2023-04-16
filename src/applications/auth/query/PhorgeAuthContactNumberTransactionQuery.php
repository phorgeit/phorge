<?php

final class PhorgeAuthContactNumberTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeAuthContactNumberTransaction();
  }

}
