<?php

final class PhorgeDashboardChartPanelChartTransaction
  extends PhorgeDashboardPanelPropertyTransaction {

  const TRANSACTIONTYPE = 'chart.chartKey';

  protected function getPropertyKey() {
    return 'chartKey';
  }

}
