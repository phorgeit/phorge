<?php

final class PhorgeGeneralCachePurger
  extends PhorgeCachePurger {

  const PURGERKEY = 'general';

  public function purgeCache() {
    $table = new PhorgeMarkupCache();
    $conn = $table->establishConnection('w');

    queryfx(
      $conn,
      'TRUNCATE TABLE %T',
      'cache_general');
  }

}
