<?php

final class PhorgeNotificationDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'notifications';

  public function getExtensionName() {
    return pht('Notifications');
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $table = new PhorgeFeedStoryNotification();
    $conn_w = $table->establishConnection('w');

    queryfx(
      $conn_w,
      'DELETE FROM %T WHERE primaryObjectPHID = %s',
      $table->getTableName(),
      $object->getPHID());
  }

}
