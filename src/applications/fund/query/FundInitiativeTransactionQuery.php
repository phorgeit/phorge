<?php

final class FundInitiativeTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new FundInitiativeTransaction();
  }

}
