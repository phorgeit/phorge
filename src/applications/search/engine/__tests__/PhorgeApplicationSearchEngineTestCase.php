<?php

final class PhorgeApplicationSearchEngineTestCase
  extends PhorgeTestCase {

  public function testGetAllEngines() {
    PhorgeApplicationSearchEngine::getAllEngines();
    $this->assertTrue(true);
  }

}
