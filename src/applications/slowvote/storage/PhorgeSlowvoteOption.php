<?php

final class PhorgeSlowvoteOption extends PhorgeSlowvoteDAO {

  protected $pollID;
  protected $name;

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'pollID' => array(
          'columns' => array('pollID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

}
