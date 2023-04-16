<?php

final class NuanceItemTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new NuanceItemTransaction();
  }

}
