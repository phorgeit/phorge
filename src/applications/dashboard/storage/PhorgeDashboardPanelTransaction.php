<?php

final class PhorgeDashboardPanelTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'dashboard';
  }

  public function getApplicationTransactionType() {
    return PhorgeDashboardPanelPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeDashboardPanelTransactionType';
  }

}
