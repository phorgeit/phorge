<?php

final class PhorgeConfigDatabaseSource
  extends PhorgeConfigProxySource {

  public function __construct($namespace) {
    $config = $this->loadConfig($namespace);
    $this->setSource(new PhorgeConfigDictionarySource($config));
  }

  public function isWritable() {
    // While this is writable, writes occur through the Config application.
    return false;
  }

  private function loadConfig($namespace) {
    $objects = id(new PhorgeConfigEntry())->loadAllWhere(
      'namespace = %s AND isDeleted = 0',
      $namespace);
    return mpull($objects, 'getValue', 'getConfigKey');
  }

}
