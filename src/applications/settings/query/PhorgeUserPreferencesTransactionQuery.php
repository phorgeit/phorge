<?php

final class PhorgeUserPreferencesTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeUserPreferencesTransaction();
  }

}
