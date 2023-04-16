<?php

final class PhorgeDashboardPortalTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeDashboardPortalTransaction();
  }

}
