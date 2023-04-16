<?php

final class PhorgeSetupCheckTestCase extends PhorgeTestCase {

  public function testLoadAllChecks() {
    PhorgeSetupCheck::loadAllChecks();
    $this->assertTrue(true);
  }

}
