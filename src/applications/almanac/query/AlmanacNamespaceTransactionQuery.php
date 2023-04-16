<?php

final class AlmanacNamespaceTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacNamespaceTransaction();
  }

}
