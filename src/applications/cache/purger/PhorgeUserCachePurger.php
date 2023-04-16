<?php

final class PhorgeUserCachePurger
  extends PhorgeCachePurger {

  const PURGERKEY = 'user';

  public function purgeCache() {
    $table = new PhorgeUserCache();
    $conn = $table->establishConnection('w');

    queryfx(
      $conn,
      'TRUNCATE TABLE %T',
      $table->getTableName());
  }

}
