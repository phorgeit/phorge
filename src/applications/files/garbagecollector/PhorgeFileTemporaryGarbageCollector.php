<?php

final class PhorgeFileTemporaryGarbageCollector
  extends PhorgeGarbageCollector {

  const COLLECTORCONST = 'files.ttl';

  public function getCollectorName() {
    return pht('Files (TTL)');
  }

  public function hasAutomaticPolicy() {
    return true;
  }

  protected function collectGarbage() {
    $files = id(new PhorgeFile())->loadAllWhere(
      'ttl < %d LIMIT 100',
      PhorgeTime::getNow());

    $engine = new PhorgeDestructionEngine();

    foreach ($files as $file) {
      $engine->destroyObject($file);
    }

    return (count($files) == 100);
  }

}
