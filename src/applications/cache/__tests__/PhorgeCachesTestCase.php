<?php

final class PhorgeCachesTestCase
  extends PhorgeTestCase {

  public function testRequestCache() {
    $cache = PhorgeCaches::getRequestCache();

    $test_key = 'unit.'.Filesystem::readRandomCharacters(8);

    $default_value = pht('Default');
    $new_value = pht('New Value');

    $this->assertEqual(
      $default_value,
      $cache->getKey($test_key, $default_value));

    // Set a key, verify it persists.
    $cache = PhorgeCaches::getRequestCache();
    $cache->setKey($test_key, $new_value);
    $this->assertEqual(
      $new_value,
      $cache->getKey($test_key, $default_value));

    // Refetch the cache, verify it's really a cache.
    $cache = PhorgeCaches::getRequestCache();
    $this->assertEqual(
      $new_value,
      $cache->getKey($test_key, $default_value));

    // Destroy the cache.
    PhorgeCaches::destroyRequestCache();

    // Now, the value should be missing again.
    $cache = PhorgeCaches::getRequestCache();
    $this->assertEqual(
      $default_value,
      $cache->getKey($test_key, $default_value));
  }

}
