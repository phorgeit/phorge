<?php

final class PhorgeChangesetCachePurger
  extends PhorgeCachePurger {

  const PURGERKEY = 'changeset';

  public function purgeCache() {
    $table = new DifferentialChangeset();
    $conn = $table->establishConnection('w');

    queryfx(
      $conn,
      'TRUNCATE TABLE %T',
      DifferentialChangeset::TABLE_CACHE);
  }

}
