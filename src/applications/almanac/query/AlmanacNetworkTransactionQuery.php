<?php

final class AlmanacNetworkTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new AlmanacNetworkTransaction();
  }

}
