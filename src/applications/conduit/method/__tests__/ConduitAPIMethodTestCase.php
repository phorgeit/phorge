<?php

final class ConduitAPIMethodTestCase extends PhorgeTestCase {

  public function testLoadAllConduitMethods() {
    ConduitAPIMethod::loadAllConduitMethods();
    $this->assertTrue(true);
  }

}
