<?php

final class PhorgeWorkerBulkJobTestCase extends PhorgeTestCase {

  public function testGetAllBulkJobTypes() {
    PhorgeWorkerBulkJobType::getAllJobTypes();
    $this->assertTrue(true);
  }

}
