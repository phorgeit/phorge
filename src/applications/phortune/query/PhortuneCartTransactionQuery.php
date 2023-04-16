<?php

final class PhortuneCartTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortuneCartTransaction();
  }

}
