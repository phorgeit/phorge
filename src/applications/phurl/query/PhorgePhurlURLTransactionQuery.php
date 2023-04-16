<?php

final class PhorgePhurlURLTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgePhurlURLTransaction();
  }

}
