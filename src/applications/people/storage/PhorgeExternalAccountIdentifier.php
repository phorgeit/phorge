<?php

final class PhorgeExternalAccountIdentifier
  extends PhorgeUserDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  protected $externalAccountPHID;
  protected $providerConfigPHID;
  protected $identifierHash;
  protected $identifierRaw;

  public function getPHIDType() {
    return PhorgePeopleExternalIdentifierPHIDType::TYPECONST;
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'identifierHash' => 'bytes12',
        'identifierRaw' => 'text',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_identifier' => array(
          'columns' => array('providerConfigPHID', 'identifierHash'),
          'unique' => true,
        ),
        'key_account' => array(
          'columns' => array('externalAccountPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function save() {
    $identifier_raw = $this->getIdentifierRaw();

    $identifier_hash = PhorgeHash::digestForIndex($identifier_raw);
    $this->setIdentifierHash($identifier_hash);

    return parent::save();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */

  // TODO: These permissions aren't very good. They should just be the same
  // as the associated ExternalAccount. See T13381.

  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::getMostOpenPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return PhorgePolicies::POLICY_NOONE;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }

}
