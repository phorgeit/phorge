<?php

final class FundInitiativeTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new FundInitiativeTransaction();
  }

}
