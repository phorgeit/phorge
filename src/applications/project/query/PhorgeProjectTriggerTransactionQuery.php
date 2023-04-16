<?php

final class PhorgeProjectTriggerTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeProjectTriggerTransaction();
  }

}
