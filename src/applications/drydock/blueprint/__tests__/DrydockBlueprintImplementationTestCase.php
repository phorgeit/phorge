<?php

final class DrydockBlueprintImplementationTestCase extends PhorgeTestCase {

  public function testGetAllBlueprintImplementations() {
    DrydockBlueprintImplementation::getAllBlueprintImplementations();
    $this->assertTrue(true);
  }

}
