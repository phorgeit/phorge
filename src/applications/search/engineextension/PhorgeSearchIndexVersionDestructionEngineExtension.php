<?php

final class PhorgeSearchIndexVersionDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'search.index.version';

  public function getExtensionName() {
    return pht('Search Index Versions');
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $table = new PhorgeSearchIndexVersion();

    queryfx(
      $table->establishConnection('w'),
      'DELETE FROM %T WHERE objectPHID = %s',
      $table->getTableName(),
      $object->getPHID());
  }

}
