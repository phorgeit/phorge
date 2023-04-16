<?php

final class PhorgeAuthFactorTestCase extends PhorgeTestCase {

  public function testGetAllFactors() {
    PhorgeAuthFactor::getAllFactors();
    $this->assertTrue(true);
  }

}
