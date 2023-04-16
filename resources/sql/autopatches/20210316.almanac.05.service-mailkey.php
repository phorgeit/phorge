<?php

$service_table = new AlmanacService();
$service_conn = $service_table->establishConnection('w');

$properties_table = new PhorgeMetaMTAMailProperties();
$conn = $properties_table->establishConnection('w');

$iterator = new LiskRawMigrationIterator(
  $service_conn,
  $service_table->getTableName());

foreach ($iterator as $row) {
  queryfx(
    $conn,
    'INSERT IGNORE INTO %R
        (objectPHID, mailProperties, dateCreated, dateModified)
      VALUES
        (%s, %s, %d, %d)',
    $properties_table,
    $row['phid'],
    phutil_json_encode(
      array(
        'mailKey' => $row['mailKey'],
      )),
    PhorgeTime::getNow(),
    PhorgeTime::getNow());
}
