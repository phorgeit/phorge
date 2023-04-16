<?php

final class AlmanacBindingTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacBindingTransaction();
  }

}
