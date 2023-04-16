<?php

final class FundBackerTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new FundBackerTransaction();
  }

}
