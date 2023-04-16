<?php

final class PhorgeDashboardPanelTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeDashboardPanelTransaction();
  }

}
