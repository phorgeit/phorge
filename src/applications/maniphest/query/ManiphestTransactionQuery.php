<?php

final class ManiphestTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new ManiphestTransaction();
  }

}
