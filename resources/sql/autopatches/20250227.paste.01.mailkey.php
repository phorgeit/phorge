<?php

$paste_table = new PhabricatorPaste();
$paste_conn = $paste_table->establishConnection('w');

$properties_table = new PhabricatorMetaMTAMailProperties();
$conn = $properties_table->establishConnection('w');

$iterator = new LiskRawMigrationIterator(
  $paste_conn,
  $paste_table->getTableName());

foreach ($iterator as $row) {
  // The mailKey field might be unpopulated.
  // This should have happened in the 20130805.pastemailkeypop.php migration,
  // but that will not work on newer installations, because the paste table
  // was renamed in between.
  $mailkey = $row['mailKey'] ?? Filesystem::readRandomCharacters(20);

  queryfx(
    $conn,
    'INSERT IGNORE INTO %T
        (objectPHID, mailProperties, dateCreated, dateModified)
      VALUES
        (%s, %s, %d, %d)',
    $properties_table->getTableName(),
    $row['phid'],
    phutil_json_encode(
      array(
        'mailKey' => $mailkey,
      )),
    PhabricatorTime::getNow(),
    PhabricatorTime::getNow());
}
