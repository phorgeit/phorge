<?php

final class PhorgeEdgeTypeTestCase extends PhorgeTestCase {

  public function testGetAllTypes() {
    PhorgeEdgeType::getAllTypes();
    $this->assertTrue(true);
  }

}
