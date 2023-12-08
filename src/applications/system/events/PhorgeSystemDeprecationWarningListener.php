<?php

final class PhorgeSystemDeprecationWarningListener
  extends PhabricatorEventListener {

  const CACHE_KEY = 'setup-check:deprecation-warnings';
  const MAX_ENTRIES = 5;

  public function handleEvent(PhutilEvent $event) {
    // we're not an actual PhutilEventListener - we're just using the `register`
    // part of that framework.
  }

  public function register() {
    PhutilErrorHandler::addErrorListener(
      array($this, 'handleErrors'));
  }

  public function handleErrors($event, $value, $metadata) {

    if ($event !== PhutilErrorHandler::DEPRECATED) {
      return;
    }

    $trace_key = sprintf(
      '%s:%s',
      basename($metadata['file']),
      $metadata['line']);

    $cache = PhabricatorCaches::getRuntimeCache();
    $cache_entry = $cache->getKey(self::CACHE_KEY);

    if (!$cache_entry) {
      $cache_entry = array();
    }

    $trace_entry = idx($cache_entry, $trace_key);

    if ($trace_entry) {
      $trace_entry['counter']++;
    } else {
      $trace_entry = array(
        'counter' => 1,
        'message' => $value,
        'trace' => PhutilErrorHandler::formatStacktrace($metadata['trace']),
      );
    }
    $cache_entry[$trace_key] = $trace_entry;

    $cache->setKey(self::CACHE_KEY , $cache_entry);
  }

  public function getWarnings() {
    $cache = PhabricatorCaches::getRuntimeCache();
    return $cache->getKey(self::CACHE_KEY);
  }

}
