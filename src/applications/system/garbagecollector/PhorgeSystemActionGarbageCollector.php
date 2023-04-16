<?php

final class PhorgeSystemActionGarbageCollector
  extends PhorgeGarbageCollector {

  const COLLECTORCONST = 'system.actions';

  public function getCollectorName() {
    return pht('Rate Limiting Actions');
  }

  public function getDefaultRetentionPolicy() {
    return phutil_units('3 days in seconds');
  }

  protected function collectGarbage() {
    $table = new PhorgeSystemActionLog();
    $conn_w = $table->establishConnection('w');

    queryfx(
      $conn_w,
      'DELETE FROM %T WHERE epoch < %d LIMIT 100',
      $table->getTableName(),
      $this->getGarbageEpoch());

    return ($conn_w->getAffectedRows() == 100);
  }

}
