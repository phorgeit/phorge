<?php

final class PhorgeDashboardTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'dashboard';
  }

  public function getApplicationTransactionType() {
    return PhorgeDashboardDashboardPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeDashboardTransactionType';
  }

}
