<?php

final class HeraldWebhookTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HeraldWebhookTransaction();
  }

}
