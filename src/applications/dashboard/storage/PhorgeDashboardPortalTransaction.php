<?php

final class PhorgeDashboardPortalTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'dashboard';
  }

  public function getApplicationTransactionType() {
    return PhorgeDashboardPortalPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeDashboardPortalTransactionType';
  }

}
