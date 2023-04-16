<?php

final class PhorgeDashboardTabsPanelTabsTransaction
  extends PhorgeDashboardPanelPropertyTransaction {

  const TRANSACTIONTYPE = 'tabs.tabs';

  protected function getPropertyKey() {
    return 'config';
  }

}
