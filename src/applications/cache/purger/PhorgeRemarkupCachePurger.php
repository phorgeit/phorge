<?php

final class PhorgeRemarkupCachePurger
  extends PhorgeCachePurger {

  const PURGERKEY = 'remarkup';

  public function purgeCache() {
    $table = new PhorgeMarkupCache();
    $conn = $table->establishConnection('w');

    queryfx(
      $conn,
      'TRUNCATE TABLE %T',
      $table->getTableName());
  }

}
