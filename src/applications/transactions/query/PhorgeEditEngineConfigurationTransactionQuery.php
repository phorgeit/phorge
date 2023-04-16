<?php

final class PhorgeEditEngineConfigurationTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeEditEngineConfigurationTransaction();
  }

}
