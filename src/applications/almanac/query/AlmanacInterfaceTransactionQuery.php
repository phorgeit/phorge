<?php

final class AlmanacInterfaceTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacInterfaceTransaction();
  }

}
