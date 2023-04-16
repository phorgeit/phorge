<?php

final class PhorgeSlowvoteTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeSlowvoteTransaction();
  }

}
