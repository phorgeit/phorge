<?php

/**
 * A collection of dashboard panels with a specific layout.
 */
final class PhorgeDashboard extends PhorgeDashboardDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeFlaggableInterface,
    PhorgeDestructibleInterface,
    PhorgeProjectInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface,
    PhorgeDashboardPanelContainerInterface {

  protected $name;
  protected $authorPHID;
  protected $viewPolicy;
  protected $editPolicy;
  protected $status;
  protected $icon;
  protected $layoutConfig = array();

  const STATUS_ACTIVE = 'active';
  const STATUS_ARCHIVED = 'archived';

  private $panelRefList;

  public static function initializeNewDashboard(PhorgeUser $actor) {
    return id(new PhorgeDashboard())
      ->setName('')
      ->setIcon('fa-dashboard')
      ->setViewPolicy(PhorgePolicies::getMostOpenPolicy())
      ->setEditPolicy($actor->getPHID())
      ->setStatus(self::STATUS_ACTIVE)
      ->setAuthorPHID($actor->getPHID());
  }

  public static function getStatusNameMap() {
    return array(
      self::STATUS_ACTIVE => pht('Active'),
      self::STATUS_ARCHIVED => pht('Archived'),
    );
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'layoutConfig' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort255',
        'status' => 'text32',
        'icon' => 'text32',
        'authorPHID' => 'phid',
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeDashboardDashboardPHIDType::TYPECONST;
  }

  public function getRawLayoutMode() {
    $config = $this->getRawLayoutConfig();
    return idx($config, 'layoutMode');
  }

  public function setRawLayoutMode($mode) {
    $config = $this->getRawLayoutConfig();
    $config['layoutMode'] = $mode;
    return $this->setRawLayoutConfig($config);
  }

  public function getRawPanels() {
    $config = $this->getRawLayoutConfig();
    return idx($config, 'panels');
  }

  public function setRawPanels(array $panels) {
    $config = $this->getRawLayoutConfig();
    $config['panels'] = $panels;
    return $this->setRawLayoutConfig($config);
  }

  private function getRawLayoutConfig() {
    $config = $this->getLayoutConfig();

    if (!is_array($config)) {
      $config = array();
    }

    return $config;
  }

  private function setRawLayoutConfig(array $config) {
    // If a cached panel ref list exists, clear it.
    $this->panelRefList = null;

    return $this->setLayoutConfig($config);
  }

  public function isArchived() {
    return ($this->getStatus() == self::STATUS_ARCHIVED);
  }

  public function getURI() {
    return urisprintf('/dashboard/view/%d/', $this->getID());
  }

  public function getObjectName() {
    return pht('Dashboard %d', $this->getID());
  }

  public function getPanelRefList() {
    if (!$this->panelRefList) {
      $this->panelRefList = $this->newPanelRefList();
    }
    return $this->panelRefList;
  }

  private function newPanelRefList() {
    $raw_config = $this->getLayoutConfig();
    return PhorgeDashboardPanelRefList::newFromDictionary($raw_config);
  }

  public function getPanelPHIDs() {
    $ref_list = $this->getPanelRefList();
    $phids = mpull($ref_list->getPanelRefs(), 'getPanelPHID');
    return array_unique($phids);
  }

/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeDashboardTransactionEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeDashboardTransaction();
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

/* -(  PhorgeDashboardPanelContainerInterface  )------------------------ */

  public function getDashboardPanelContainerPanelPHIDs() {
    return $this->getPanelPHIDs();
  }

/* -(  PhorgeFulltextInterface  )--------------------------------------- */

  public function newFulltextEngine() {
    return new PhorgeDashboardFulltextEngine();
  }

/* -(  PhorgeFerretInterface  )----------------------------------------- */

  public function newFerretEngine() {
    return new PhorgeDashboardFerretEngine();
  }

}
