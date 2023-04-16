<?php

final class PhortuneSubscriptionTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortuneSubscriptionTransaction();
  }

}
