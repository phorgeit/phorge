<?php

final class PhorgeDashboardQueryPanelQueryTransaction
  extends PhorgeDashboardPanelPropertyTransaction {

  const TRANSACTIONTYPE = 'search.query';

  protected function getPropertyKey() {
    return 'key';
  }

}
