<?php

final class PhorgeFileExternalRequestGarbageCollector
  extends PhorgeGarbageCollector {

  const COLLECTORCONST = 'files.externalttl';

  public function getCollectorName() {
    return pht('External Requests (TTL)');
  }

  public function hasAutomaticPolicy() {
    return true;
  }

  protected function collectGarbage() {
    $file_requests = id(new PhorgeFileExternalRequest())->loadAllWhere(
      'ttl < %d LIMIT 100',
      PhorgeTime::getNow());
    $engine = new PhorgeDestructionEngine();
    foreach ($file_requests as $request) {
      $engine->destroyObject($request);
    }

    return (count($file_requests) == 100);
  }

}
