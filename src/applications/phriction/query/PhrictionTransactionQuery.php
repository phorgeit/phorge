<?php

final class PhrictionTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhrictionTransaction();
  }

}
