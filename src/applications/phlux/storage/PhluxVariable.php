<?php

final class PhluxVariable extends PhluxDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgeFlaggableInterface,
    PhorgePolicyInterface {

  protected $variableKey;
  protected $variableValue;
  protected $viewPolicy;
  protected $editPolicy;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'variableValue' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'variableKey' => 'text64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_key' => array(
          'columns' => array('variableKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(PhluxVariablePHIDType::TYPECONST);
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhluxVariableEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhluxTransaction();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return $this->viewPolicy;
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->editPolicy;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
