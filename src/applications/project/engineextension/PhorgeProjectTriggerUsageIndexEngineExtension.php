<?php

final class PhorgeProjectTriggerUsageIndexEngineExtension
  extends PhorgeIndexEngineExtension {

  const EXTENSIONKEY = 'trigger.usage';

  public function getExtensionName() {
    return pht('Trigger Usage');
  }

  public function shouldIndexObject($object) {
    if (!($object instanceof PhorgeProjectTrigger)) {
      return false;
    }

    return true;
  }

  public function indexObject(
    PhorgeIndexEngine $engine,
    $object) {

    $usage_table = new PhorgeProjectTriggerUsage();
    $column_table = new PhorgeProjectColumn();

    $conn_w = $object->establishConnection('w');

    $active_statuses = array(
      PhorgeProjectColumn::STATUS_ACTIVE,
    );

    // Select summary information to populate the usage index. When picking
    // an "examplePHID", we try to pick an active column.
    $row = queryfx_one(
      $conn_w,
      'SELECT phid, COUNT(*) N, SUM(IF(status IN (%Ls), 1, 0)) M FROM %R
        WHERE triggerPHID = %s
        ORDER BY IF(status IN (%Ls), 1, 0) DESC, id ASC',
      $active_statuses,
      $column_table,
      $object->getPHID(),
      $active_statuses);
    if ($row) {
      $example_phid = $row['phid'];
      $column_count = $row['N'];
      $active_count = $row['M'];
    } else {
      $example_phid = null;
      $column_count = 0;
      $active_count = 0;
    }

    queryfx(
      $conn_w,
      'INSERT INTO %R (triggerPHID, examplePHID, columnCount, activeColumnCount)
        VALUES (%s, %ns, %d, %d)
        ON DUPLICATE KEY UPDATE
          examplePHID = VALUES(examplePHID),
          columnCount = VALUES(columnCount),
          activeColumnCount = VALUES(activeColumnCount)',
      $usage_table,
      $object->getPHID(),
      $example_phid,
      $column_count,
      $active_count);
  }

}
