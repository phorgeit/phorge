<?php

final class NuanceQueue
  extends NuanceDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface {

  protected $name;
  protected $mailKey;
  protected $viewPolicy;
  protected $editPolicy;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255?',
        'mailKey' => 'bytes20',
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      NuanceQueuePHIDType::TYPECONST);
  }

  public static function initializeNewQueue() {
    return id(new self())
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->setEditPolicy(PhorgePolicies::POLICY_USER);
  }

  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }

  public function getURI() {
    return '/nuance/queue/view/'.$this->getID().'/';
  }

  public function getWorkURI() {
    return '/nuance/queue/work/'.$this->getID().'/';
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
        return $this->getViewPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new NuanceQueueEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new NuanceQueueTransaction();
  }

}
