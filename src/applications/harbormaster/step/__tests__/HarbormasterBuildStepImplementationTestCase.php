<?php

final class HarbormasterBuildStepImplementationTestCase
  extends PhorgeTestCase {

  public function testGetImplementations() {
    HarbormasterBuildStepImplementation::getImplementations();
    $this->assertTrue(true);
  }

}
