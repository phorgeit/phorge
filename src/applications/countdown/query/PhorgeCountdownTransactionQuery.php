<?php

final class PhorgeCountdownTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeCountdownTransaction();
  }

}
