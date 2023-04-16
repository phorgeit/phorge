<?php

final class PhortuneAccountEmailTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortuneAccountEmailTransaction();
  }

}
