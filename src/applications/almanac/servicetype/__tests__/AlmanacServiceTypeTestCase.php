<?php

final class AlmanacServiceTypeTestCase extends PhorgeTestCase {

  public function testGetAllServiceTypes() {
    AlmanacServiceType::getAllServiceTypes();
    $this->assertTrue(true);
  }

}
