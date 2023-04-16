<?php

final class PhorgeProjectTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeProjectTransaction();
  }

}
