<?php

final class PhorgeCacheGeneralGarbageCollector
  extends PhorgeGarbageCollector {

  const COLLECTORCONST = 'cache.general';

  public function getCollectorName() {
    return pht('General Cache');
  }

  public function getDefaultRetentionPolicy() {
    return phutil_units('30 days in seconds');
  }

  protected function collectGarbage() {
    $cache = new PhorgeKeyValueDatabaseCache();
    $conn_w = $cache->establishConnection('w');

    queryfx(
      $conn_w,
      'DELETE FROM %T WHERE cacheCreated < %d
        ORDER BY cacheCreated ASC LIMIT 100',
      $cache->getTableName(),
      $this->getGarbageEpoch());

    return ($conn_w->getAffectedRows() == 100);
  }

}
