<?php

abstract class PhorgeCustomFieldIndexStorage extends PhorgeLiskDAO {

  protected $objectPHID;
  protected $indexKey;
  protected $indexValue;

  protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
    ) + parent::getConfiguration();
  }

  abstract public function formatForInsert(AphrontDatabaseConnection $conn);
  abstract public function getIndexValueType();

}
