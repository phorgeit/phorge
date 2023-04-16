<?php

final class HeraldTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HeraldRuleTransaction();
  }

}
