<?php

final class PhorgeDashboardTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeDashboardTransaction();
  }

}
