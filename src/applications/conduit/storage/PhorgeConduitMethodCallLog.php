<?php

final class PhorgeConduitMethodCallLog
  extends PhorgeConduitDAO
  implements PhorgePolicyInterface {

  protected $callerPHID;
  protected $connectionID;
  protected $method;
  protected $error;
  protected $duration;

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'id' => 'auto64',
        'connectionID' => 'id64?',
        'method' => 'text64',
        'error' => 'text255',
        'duration' => 'uint64',
        'callerPHID' => 'phid?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_date' => array(
          'columns' => array('dateCreated'),
        ),
        'key_method' => array(
          'columns' => array('method'),
        ),
        'key_callermethod' => array(
          'columns' => array('callerPHID', 'method'),
        ),
      ),
    ) + parent::getConfiguration();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return PhorgePolicies::POLICY_USER;
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
