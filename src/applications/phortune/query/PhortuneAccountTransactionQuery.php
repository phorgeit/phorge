<?php

final class PhortuneAccountTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortuneAccountTransaction();
  }

}
