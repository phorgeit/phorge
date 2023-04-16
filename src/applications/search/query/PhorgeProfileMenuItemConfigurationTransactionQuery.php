<?php

final class PhorgeProfileMenuItemConfigurationTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeProfileMenuItemConfigurationTransaction();
  }

}
