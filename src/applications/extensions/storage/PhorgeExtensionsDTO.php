<?php

abstract class PhorgeExtensionsDTO extends PhabricatorLiskDAO {

  public function getApplicationName() {
    return 'extensions';
  }

  /** for now, none of the data from this app is stored in mysql. */
  final protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_NO_TABLE => true,
    ) + parent::getConfiguration();
  }

}
