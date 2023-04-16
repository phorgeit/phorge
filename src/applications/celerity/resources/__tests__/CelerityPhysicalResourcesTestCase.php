<?php

final class CelerityPhysicalResourcesTestCase extends PhorgeTestCase {

  public function testGetAll() {
    CelerityPhysicalResources::getAll();
    $this->assertTrue(true);
  }

}
