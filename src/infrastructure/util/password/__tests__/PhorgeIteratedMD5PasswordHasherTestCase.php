<?php

final class PhorgeIteratedMD5PasswordHasherTestCase
  extends PhorgeTestCase {

  public function testHasher() {
    $hasher = new PhorgeIteratedMD5PasswordHasher();

    $this->assertEqual(
      'md5:4824a35493d8b5dceab36f017d68425f',
      $hasher->getPasswordHashForStorage(
        new PhutilOpaqueEnvelope('quack'))->openEnvelope());
  }

}
