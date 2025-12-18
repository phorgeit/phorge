<?php

/**
 * Interface to the APC key-value cache. This is a very high-performance cache
 * which is local to the current machine.
 */
final class PhutilAPCKeyValueCache extends PhutilKeyValueCache {


/* -(  Key-Value Cache Implementation  )------------------------------------- */


  public function isAvailable() {
    return function_exists('apcu_fetch') &&
           ini_get('apc.enabled') &&
           (ini_get('apc.enable_cli') || php_sapi_name() != 'cli');
  }

  public function getKeys(array $keys, $ttl = null) {
    static $is_apcu;
    if ($is_apcu === null) {
      $is_apcu = self::isAPCu();
    }

    $results = array();
    $fetched = false;
    foreach ($keys as $key) {
      if (!$is_apcu) {
        continue;
      }
      $result = apcu_fetch($key, $fetched);
      if ($fetched) {
        $results[$key] = $result;
      }
    }
    return $results;
  }

  public function setKeys(array $keys, $ttl = 0) {
    static $is_apcu;
    if ($is_apcu === null) {
      $is_apcu = self::isAPCu();
    }

    if ($ttl === null) {
      $ttl = 0;
    }

    // NOTE: Although late APC supported passing an array to `apc_store()`,
    // it was not supported by older versions of APC or by HPHP.

    // See T13525 for discussion of use of "@" to silence this warning:
    // > GC cache entry "<some-key-name>" was on gc-list for <X> seconds

    foreach ($keys as $key => $value) {
      if ($is_apcu) {
        @apcu_store($key, $value, $ttl);
      }
    }

    return $this;
  }

  public function deleteKeys(array $keys) {
    static $is_apcu;
    if ($is_apcu === null) {
      $is_apcu = self::isAPCu();
    }

    foreach ($keys as $key) {
      if ($is_apcu) {
        apcu_delete($key);
      }
    }

    return $this;
  }

  public function destroyCache() {
    static $is_apcu;
    if ($is_apcu === null) {
      $is_apcu = self::isAPCu();
    }

    if ($is_apcu) {
      apcu_clear_cache();
    }

    return $this;
  }

  private static function isAPCu() {
    return function_exists('apcu_fetch');
  }

}
