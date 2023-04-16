<?php

final class HarbormasterObject extends HarbormasterDAO {

  protected $name;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255?',
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePHIDConstants::PHID_TYPE_TOBJ);
  }

}
