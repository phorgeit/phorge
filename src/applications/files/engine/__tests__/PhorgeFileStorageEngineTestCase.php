<?php

final class PhorgeFileStorageEngineTestCase extends PhorgeTestCase {

  public function testLoadAllEngines() {
    PhorgeFileStorageEngine::loadAllEngines();
    $this->assertTrue(true);
  }

}
