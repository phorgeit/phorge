<?php

final class PhorgeConfigEntry
  extends PhorgeConfigEntryDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface {

  protected $namespace;
  protected $configKey;
  protected $value;
  protected $isDeleted;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'value' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'namespace' => 'text64',
        'configKey' => 'text64',
        'isDeleted' => 'bool',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_name' => array(
          'columns' => array('namespace', 'configKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeConfigConfigPHIDType::TYPECONST);
  }

  public static function loadConfigEntry($key) {
    $config_entry = id(new PhorgeConfigEntry())
      ->loadOneWhere(
        'configKey = %s AND namespace = %s',
        $key,
        'default');

    if (!$config_entry) {
      $config_entry = id(new PhorgeConfigEntry())
        ->setConfigKey($key)
        ->setNamespace('default')
        ->setIsDeleted(0);
    }

    return $config_entry;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeConfigEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeConfigTransaction();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return PhorgePolicies::POLICY_ADMIN;
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
