<?php

final class PhorgePeopleTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeUserTransaction();
  }

}
