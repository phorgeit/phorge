<?php

final class PhorgeRepositoryPullLocalDaemonModule
  extends PhutilDaemonOverseerModule {

  private $cursor = 0;

  public function shouldWakePool(PhutilDaemonPool $pool) {
    $class = $pool->getPoolDaemonClass();
    if ($class != 'PhorgeRepositoryPullLocalDaemon') {
      return false;
    }

    if ($this->shouldThrottle($class, 1)) {
      return false;
    }

    $table = new PhorgeRepositoryStatusMessage();
    $table_name = $table->getTableName();
    $conn = $table->establishConnection('r');

    $row = queryfx_one(
      $conn,
      'SELECT id FROM %T WHERE statusType = %s
        AND id > %d ORDER BY id DESC LIMIT 1',
      $table_name,
      PhorgeRepositoryStatusMessage::TYPE_NEEDS_UPDATE,
      $this->cursor);

    if (!$row) {
      return false;
    }

    $this->cursor = (int)$row['id'];
    return true;
  }

}
