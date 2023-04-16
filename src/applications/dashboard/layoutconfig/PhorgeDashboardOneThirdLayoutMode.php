<?php

final class PhorgeDashboardOneThirdLayoutMode
  extends PhorgeDashboardLayoutMode {

  const LAYOUTMODE = 'layout-mode-third-and-thirds';

  public function getLayoutModeOrder() {
    return 700;
  }

  public function getLayoutModeName() {
    return pht('Two Columns: 33%%/66%%');
  }

  public function getLayoutModeColumns() {
    return array(
      $this->newColumn()
        ->setColumnKey('left')
        ->addClass('third'),
      $this->newColumn()
        ->setColumnKey('right')
        ->addClass('thirds'),
    );
  }

}
