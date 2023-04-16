<?php

final class PhluxTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhluxTransaction();
  }

}
