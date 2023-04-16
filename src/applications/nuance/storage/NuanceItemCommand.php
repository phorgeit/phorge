<?php

final class NuanceItemCommand
  extends NuanceDAO
  implements PhorgePolicyInterface {

  const STATUS_ISSUED = 'issued';
  const STATUS_EXECUTING = 'executing';
  const STATUS_DONE = 'done';
  const STATUS_FAILED = 'failed';

  protected $itemPHID;
  protected $authorPHID;
  protected $queuePHID;
  protected $command;
  protected $status;
  protected $parameters = array();

  public static function initializeNewCommand() {
    return id(new self())
      ->setStatus(self::STATUS_ISSUED);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_SERIALIZATION => array(
        'parameters' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'command' => 'text64',
        'status' => 'text64',
        'queuePHID' => 'phid?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_pending' => array(
          'columns' => array('itemPHID', 'status'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public static function getStatusMap() {
    return array(
      self::STATUS_ISSUED => array(
        'name' => pht('Issued'),
        'icon' => 'fa-clock-o',
        'color' => 'bluegrey',
      ),
      self::STATUS_EXECUTING => array(
        'name' => pht('Executing'),
        'icon' => 'fa-play',
        'color' => 'green',
      ),
      self::STATUS_DONE => array(
        'name' => pht('Done'),
        'icon' => 'fa-check',
        'color' => 'blue',
      ),
      self::STATUS_FAILED => array(
        'name' => pht('Failed'),
        'icon' => 'fa-times',
        'color' => 'red',
      ),
    );
  }

  private function getStatusSpec() {
    $map = self::getStatusMap();
    return idx($map, $this->getStatus(), array());
  }

  public function getStatusIcon() {
    $spec = $this->getStatusSpec();
    return idx($spec, 'icon', 'fa-question');
  }

  public function getStatusColor() {
    $spec = $this->getStatusSpec();
    return idx($spec, 'color', 'indigo');
  }

  public function getStatusName() {
    $spec = $this->getStatusSpec();
    return idx($spec, 'name', $this->getStatus());
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return PhorgePolicies::getMostOpenPolicy();
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
