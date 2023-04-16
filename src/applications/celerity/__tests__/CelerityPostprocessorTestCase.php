<?php

final class CelerityPostprocessorTestCase extends PhorgeTestCase {

  public function testGetAllCelerityPostprocessors() {
    CelerityPostprocessor::getAllPostprocessors();
    $this->assertTrue(true);
  }

}
