<?php

$commit_table = new PhorgeRepositoryCommit();
$commit_conn = $commit_table->establishConnection('w');
$commit_name = $commit_table->getTableName();

$properties_table = new PhorgeMetaMTAMailProperties();
$conn = $properties_table->establishConnection('w');

$iterator = new LiskRawMigrationIterator($commit_conn, $commit_name);
$chunks = new PhutilChunkedIterator($iterator, 100);
foreach ($chunks as $chunk) {
  $sql = array();
  foreach ($chunk as $commit) {
    $sql[] = qsprintf(
      $conn,
      '(%s, %s, %d, %d)',
      $commit['phid'],
      phutil_json_encode(
        array(
          'mailKey' => $commit['mailKey'],
        )),
      PhorgeTime::getNow(),
      PhorgeTime::getNow());
  }

  queryfx(
    $conn,
    'INSERT IGNORE INTO %R
        (objectPHID, mailProperties, dateCreated, dateModified)
      VALUES %LQ',
    $properties_table,
    $sql);
}
