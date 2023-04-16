<?php

// Populate account passwords (which we copied from the user table in the last
// migration) with new PHIDs.

$table = new PhorgeAuthPassword();
$conn = $table->establishConnection('w');

$password_type = PhorgeAuthPasswordPHIDType::TYPECONST;

foreach (new LiskMigrationIterator($table) as $row) {
  if (phid_get_type($row->getPHID()) == $password_type) {
    continue;
  }

  $new_phid = $row->generatePHID();

  queryfx(
    $conn,
    'UPDATE %T SET phid = %s WHERE id = %d',
    $table->getTableName(),
    $new_phid,
    $row->getID());
}
