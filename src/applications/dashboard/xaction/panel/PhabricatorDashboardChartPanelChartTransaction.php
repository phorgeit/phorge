<?php

final class PhabricatorDashboardChartPanelChartTransaction
  extends PhabricatorDashboardPanelPropertyTransaction {

  const TRANSACTIONTYPE = 'chart.chartKey';

  protected function getPropertyKey() {
    return 'chartKey';
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    foreach ($xactions as $xaction) {
      $new = $xaction->getNewValue();
      if (!$this->loadChart($new)) {
        $errors[] = $this->newInvalidError(
          pht('Chart with this key does not exist. A chart must be '.
          'specified by its %d character long key.',
          12),
          $xaction);
        continue;
      }
    }
    return $errors;
  }

  private function loadChart($chart_key) {
    // PhabricatorFactChartQuery does not exist so use queryfx_one()
    $chart = new PhabricatorFactChart();
    $conn_r = $chart->establishConnection('r');
    $chart = queryfx_one(
      $conn_r,
      'SELECT
        id
        FROM %R
        WHERE chartKey = %s',
        $chart,
        $chart_key);

    return $chart;
  }

}
