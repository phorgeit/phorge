<?php

final class PhorgeHMACTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testHMACKeyGeneration() {
    $input = 'quack';

    $hash_1 = PhorgeHash::digestWithNamedKey($input, 'test');
    $hash_2 = PhorgeHash::digestWithNamedKey($input, 'test');

    $this->assertEqual($hash_1, $hash_2);
  }

  public function testSHA256Hashing() {
    $input = 'quack';
    $key = 'duck';
    $expect =
      '5274473dc34fc61bd7a6a5ff258e6505'.
      '4b26644fb7a272d74f276ab677361b9a';

    $hash = PhorgeHash::digestHMACSHA256($input, $key);
    $this->assertEqual($expect, $hash);

    $input = 'The quick brown fox jumps over the lazy dog';
    $key = 'key';
    $expect =
      'f7bc83f430538424b13298e6aa6fb143'.
      'ef4d59a14946175997479dbc2d1a3cd8';

    $hash = PhorgeHash::digestHMACSHA256($input, $key);
    $this->assertEqual($expect, $hash);
  }

}
