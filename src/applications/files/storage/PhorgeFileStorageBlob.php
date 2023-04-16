<?php

/**
 * Simple blob store DAO for @{class:PhorgeMySQLFileStorageEngine}.
 */
final class PhorgeFileStorageBlob extends PhorgeFileDAO {

  protected $data;

  protected function getConfiguration() {
    return array(
      self::CONFIG_BINARY => array(
        'data' => true,
      ),
    ) + parent::getConfiguration();
  }

}
