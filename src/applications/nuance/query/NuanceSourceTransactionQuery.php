<?php

final class NuanceSourceTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new NuanceSourceTransaction();
  }

}
