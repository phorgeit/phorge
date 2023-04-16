<?php

abstract class PhorgeFactDAO extends PhorgeLiskDAO {

  public function getApplicationName() {
    return 'fact';
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_TIMESTAMPS => false,
    ) + parent::getConfiguration();
  }

}
