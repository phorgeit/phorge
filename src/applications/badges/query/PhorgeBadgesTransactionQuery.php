<?php

final class PhorgeBadgesTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeBadgesTransaction();
  }

}
