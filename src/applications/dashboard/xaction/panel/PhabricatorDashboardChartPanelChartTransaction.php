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
      // per PhabricatorFactChart::newChartKey()
      if (strlen($new) !== 12) {
        $errors[] = $this->newInvalidError(
          pht('Chart must be specified by its %d character long key.', 12),
          $xaction);
        continue;
      }
    }
    return $errors;
  }

}
