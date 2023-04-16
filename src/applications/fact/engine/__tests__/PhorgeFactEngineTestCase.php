<?php

final class PhorgeFactEngineTestCase extends PhorgeTestCase {

  public function testLoadAllEngines() {
    PhorgeFactEngine::loadAllEngines();
    $this->assertTrue(true);
  }

}
