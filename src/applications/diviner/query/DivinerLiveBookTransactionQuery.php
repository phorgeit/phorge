<?php

final class DivinerLiveBookTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new DivinerLiveBookTransaction();
  }

}
