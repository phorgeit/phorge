<?php

/**
 * An individual dashboard panel.
 */
final class PhorgeDashboardPanel
  extends PhorgeDashboardDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeFlaggableInterface,
    PhorgeDestructibleInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface,
    PhorgeDashboardPanelContainerInterface {

  protected $name;
  protected $panelType;
  protected $viewPolicy;
  protected $editPolicy;
  protected $authorPHID;
  protected $isArchived = 0;
  protected $properties = array();

  public static function initializeNewPanel(PhorgeUser $actor) {
    return id(new PhorgeDashboardPanel())
      ->setName('')
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy(PhorgePolicies::getMostOpenPolicy())
      ->setEditPolicy($actor->getPHID());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort255',
        'panelType' => 'text64',
        'authorPHID' => 'phid',
        'isArchived' => 'bool',
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeDashboardPanelPHIDType::TYPECONST;
  }

  public function getProperty($key, $default = null) {
    return idx($this->properties, $key, $default);
  }

  public function setProperty($key, $value) {
    $this->properties[$key] = $value;
    return $this;
  }

  public function getMonogram() {
    return 'W'.$this->getID();
  }

  public function getURI() {
    return '/'.$this->getMonogram();
  }

  public function getPanelTypes() {
    $panel_types = PhorgeDashboardPanelType::getAllPanelTypes();
    $panel_types = mpull($panel_types, 'getPanelTypeName', 'getPanelTypeKey');
    asort($panel_types);
    $panel_types = (array('' => pht('(All Types)')) + $panel_types);
    return $panel_types;
  }

  public function getStatuses() {
    $statuses =
      array(
        '' => pht('(All Panels)'),
        'active' => pht('Active Panels'),
        'archived' => pht('Archived Panels'),
      );
    return $statuses;
  }

  public function getImplementation() {
    return idx(
      PhorgeDashboardPanelType::getAllPanelTypes(),
      $this->getPanelType());
  }

  public function requireImplementation() {
    $impl = $this->getImplementation();
    if (!$impl) {
      throw new Exception(
        pht(
          'Attempting to use a panel in a way that requires an '.
          'implementation, but the panel implementation ("%s") is unknown.',
          $this->getPanelType()));
    }
    return $impl;
  }

  public function getEditEngineFields() {
    return $this->requireImplementation()->getEditEngineFields($this);
  }

  public function newHeaderEditActions(
    PhorgeUser $viewer,
    $context_phid) {
    return $this->requireImplementation()->newHeaderEditActions(
      $this,
      $viewer,
      $context_phid);
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeDashboardPanelTransactionEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeDashboardPanelTransaction();
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

    $this->openTransaction();
      $this->delete();
    $this->saveTransaction();
  }

/* -(  PhorgeDashboardPanelContainerInterface  )------------------------ */

  public function getDashboardPanelContainerPanelPHIDs() {
    return $this->requireImplementation()->getSubpanelPHIDs($this);
  }

/* -(  PhorgeFulltextInterface  )--------------------------------------- */

  public function newFulltextEngine() {
    return new PhorgeDashboardPanelFulltextEngine();
  }

/* -(  PhorgeFerretInterface  )----------------------------------------- */

  public function newFerretEngine() {
    return new PhorgeDashboardPanelFerretEngine();
  }

}
