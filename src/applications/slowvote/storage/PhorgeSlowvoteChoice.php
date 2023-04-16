<?php

final class PhorgeSlowvoteChoice extends PhorgeSlowvoteDAO {

  protected $pollID;
  protected $optionID;
  protected $authorPHID;

  protected function getConfiguration() {
    return array(
      self::CONFIG_KEY_SCHEMA => array(
        'pollID' => array(
          'columns' => array('pollID'),
        ),
        'authorPHID' => array(
          'columns' => array('authorPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

}
