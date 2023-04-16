<?php

final class PhorgePolicyCapabilityTestCase
  extends PhorgeTestCase {

  public function testGetCapabilityMap() {
    PhorgePolicyCapability::getCapabilityMap();
    $this->assertTrue(true);
  }

}
