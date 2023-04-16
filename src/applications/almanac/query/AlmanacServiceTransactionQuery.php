<?php

final class AlmanacServiceTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacServiceTransaction();
  }

}
