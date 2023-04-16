<?php

final class PhorgeApplicationTestCase extends PhorgeTestCase {

  public function testGetAllApplications() {
    PhorgeApplication::getAllApplications();
    $this->assertTrue(true);
  }

}
