<?php

$table = new PhorgeOwnersPath();
$conn = $table->establishConnection('w');

foreach (new LiskMigrationIterator($table) as $path) {
  $index = PhorgeHash::digestForIndex($path->getPath());

  if ($index === $path->getPathIndex()) {
    continue;
  }

  queryfx(
    $conn,
    'UPDATE %T SET pathIndex = %s WHERE id = %d',
    $table->getTableName(),
    $index,
    $path->getID());
}
