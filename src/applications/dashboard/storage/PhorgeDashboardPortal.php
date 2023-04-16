<?php

final class PhorgeDashboardPortal
  extends PhorgeDashboardDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeDestructibleInterface,
    PhorgeProjectInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface {

  protected $name;
  protected $viewPolicy;
  protected $editPolicy;
  protected $status;
  protected $properties = array();

  public static function initializeNewPortal() {
    return id(new self())
      ->setName('')
      ->setViewPolicy(PhorgePolicies::getMostOpenPolicy())
      ->setEditPolicy(PhorgePolicies::POLICY_USER)
      ->setStatus(PhorgeDashboardPortalStatus::STATUS_ACTIVE);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255',
        'status' => 'text32',
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeDashboardPortalPHIDType::TYPECONST;
  }

  public function getPortalProperty($key, $default = null) {
    return idx($this->properties, $key, $default);
  }

  public function setPortalProperty($key, $value) {
    $this->properties[$key] = $value;
    return $this;
  }

  public function getObjectName() {
    return pht('Portal %d', $this->getID());
  }

  public function getURI() {
    return '/portal/view/'.$this->getID().'/';
  }

  public function isArchived() {
    $status_archived = PhorgeDashboardPortalStatus::STATUS_ARCHIVED;
    return ($this->getStatus() === $status_archived);
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeDashboardPortalEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeDashboardPortalTransaction();
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


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }

/* -(  PhorgeFulltextInterface  )--------------------------------------- */

  public function newFulltextEngine() {
    return new PhorgeDashboardPortalFulltextEngine();
  }

/* -(  PhorgeFerretInterface  )----------------------------------------- */

  public function newFerretEngine() {
    return new PhorgeDashboardPortalFerretEngine();
  }

}
