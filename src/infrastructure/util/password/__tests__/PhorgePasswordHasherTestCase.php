<?php

final class PhorgePasswordHasherTestCase extends PhorgeTestCase {

  public function testHasherSyntax() {
    $caught = null;
    try {
      PhorgePasswordHasher::getHasherForHash(
        new PhutilOpaqueEnvelope('xxx'));
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue(
      ($caught instanceof Exception),
      pht('Exception on unparseable hash format.'));

    $caught = null;
    try {
      PhorgePasswordHasher::getHasherForHash(
        new PhutilOpaqueEnvelope('__test__:yyy'));
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue(
      ($caught instanceof PhorgePasswordHasherUnavailableException),
      pht('Fictional hasher unavailable.'));
  }

  public function testGetAllHashers() {
    PhorgePasswordHasher::getAllHashers();
    $this->assertTrue(true);
  }

}
