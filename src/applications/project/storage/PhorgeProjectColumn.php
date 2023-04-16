<?php

final class PhorgeProjectColumn
  extends PhorgeProjectDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeDestructibleInterface,
    PhorgeExtendedPolicyInterface,
    PhorgeConduitResultInterface {

  const STATUS_ACTIVE = 0;
  const STATUS_HIDDEN = 1;

  protected $name;
  protected $status;
  protected $projectPHID;
  protected $proxyPHID;
  protected $sequence;
  protected $properties = array();
  protected $triggerPHID;

  private $project = self::ATTACHABLE;
  private $proxy = self::ATTACHABLE;
  private $trigger = self::ATTACHABLE;

  public static function initializeNewColumn(PhorgeUser $user) {
    return id(new PhorgeProjectColumn())
      ->setName('')
      ->setStatus(self::STATUS_ACTIVE)
      ->attachProxy(null);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255',
        'status' => 'uint32',
        'sequence' => 'uint32',
        'proxyPHID' => 'phid?',
        'triggerPHID' => 'phid?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_status' => array(
          'columns' => array('projectPHID', 'status', 'sequence'),
        ),
        'key_sequence' => array(
          'columns' => array('projectPHID', 'sequence'),
        ),
        'key_proxy' => array(
          'columns' => array('projectPHID', 'proxyPHID'),
          'unique' => true,
        ),
        'key_trigger' => array(
          'columns' => array('triggerPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeProjectColumnPHIDType::TYPECONST);
  }

  public function attachProject(PhorgeProject $project) {
    $this->project = $project;
    return $this;
  }

  public function getProject() {
    return $this->assertAttached($this->project);
  }

  public function attachProxy($proxy) {
    $this->proxy = $proxy;
    return $this;
  }

  public function getProxy() {
    return $this->assertAttached($this->proxy);
  }

  public function isDefaultColumn() {
    return (bool)$this->getProperty('isDefault');
  }

  public function isHidden() {
    $proxy = $this->getProxy();
    if ($proxy) {
      return $proxy->isArchived();
    }

    return ($this->getStatus() == self::STATUS_HIDDEN);
  }

  public function getDisplayName() {
    $proxy = $this->getProxy();
    if ($proxy) {
      return $proxy->getProxyColumnName();
    }

    $name = $this->getName();
    if (strlen($name)) {
      return $name;
    }

    if ($this->isDefaultColumn()) {
      return pht('Backlog');
    }

    return pht('Unnamed Column');
  }

  public function getDisplayType() {
    if ($this->isDefaultColumn()) {
      return pht('(Default)');
    }
    if ($this->isHidden()) {
      return pht('(Hidden)');
    }

    return null;
  }

  public function getDisplayClass() {
    $proxy = $this->getProxy();
    if ($proxy) {
      return $proxy->getProxyColumnClass();
    }

    return null;
  }

  public function getHeaderIcon() {
    $proxy = $this->getProxy();
    if ($proxy) {
      return $proxy->getProxyColumnIcon();
    }

    if ($this->isHidden()) {
      return 'fa-eye-slash';
    }

    return null;
  }

  public function getProperty($key, $default = null) {
    return idx($this->properties, $key, $default);
  }

  public function setProperty($key, $value) {
    $this->properties[$key] = $value;
    return $this;
  }

  public function getPointLimit() {
    return $this->getProperty('pointLimit');
  }

  public function setPointLimit($limit) {
    $this->setProperty('pointLimit', $limit);
    return $this;
  }

  public function getOrderingKey() {
    $proxy = $this->getProxy();

    // Normal columns and subproject columns go first, in a user-controlled
    // order.

    // All the milestone columns go last, in their sequential order.

    if (!$proxy || !$proxy->isMilestone()) {
      $group = 'A';
      $sequence = $this->getSequence();
    } else {
      $group = 'B';
      $sequence = $proxy->getMilestoneNumber();
    }

    return sprintf('%s%012d', $group, $sequence);
  }

  public function attachTrigger(PhorgeProjectTrigger $trigger = null) {
    $this->trigger = $trigger;
    return $this;
  }

  public function getTrigger() {
    return $this->assertAttached($this->trigger);
  }

  public function canHaveTrigger() {
    // Backlog columns and proxy (subproject / milestone) columns can't have
    // triggers because cards routinely end up in these columns through tag
    // edits rather than drag-and-drop and it would likely be confusing to
    // have these triggers act only a small fraction of the time.

    if ($this->isDefaultColumn()) {
      return false;
    }

    if ($this->getProxy()) {
      return false;
    }

    return true;
  }

  public function getWorkboardURI() {
    return $this->getProject()->getWorkboardURI();
  }

  public function getDropEffects() {
    $effects = array();

    $proxy = $this->getProxy();
    if ($proxy && $proxy->isMilestone()) {
      $effects[] = id(new PhorgeProjectDropEffect())
        ->setIcon($proxy->getProxyColumnIcon())
        ->setColor('violet')
        ->setContent(
          pht(
            'Move to milestone %s.',
            phutil_tag('strong', array(), $this->getDisplayName())));
    } else {
      $effects[] = id(new PhorgeProjectDropEffect())
        ->setIcon('fa-columns')
        ->setColor('blue')
        ->setContent(
          pht(
            'Move to column %s.',
            phutil_tag('strong', array(), $this->getDisplayName())));
    }


    if ($this->canHaveTrigger()) {
      $trigger = $this->getTrigger();
      if ($trigger) {
        foreach ($trigger->getDropEffects() as $trigger_effect) {
          $effects[] = $trigger_effect;
        }
      }
    }

    return $effects;
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */

  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The display name of the column.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('project')
        ->setType('map<string, wild>')
        ->setDescription(pht('The project the column belongs to.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('proxyPHID')
        ->setType('phid?')
        ->setDescription(
          pht(
            'For columns that proxy another object (like a subproject or '.
            'milestone), the PHID of the object they proxy.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getDisplayName(),
      'proxyPHID' => $this->getProxyPHID(),
      'project' => $this->getProject()->getRefForConduit(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

  public function getRefForConduit() {
    return array(
      'id' => (int)$this->getID(),
      'phid' => $this->getPHID(),
      'name' => $this->getDisplayName(),
    );
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeProjectColumnTransactionEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeProjectColumnTransaction();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    // NOTE: Column policies are enforced as an extended policy which makes
    // them the same as the project's policies.
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::getMostOpenPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return PhorgePolicies::POLICY_USER;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return $this->getProject()->hasAutomaticCapability(
      $capability,
      $viewer);
  }

  public function describeAutomaticCapability($capability) {
    return pht('Users must be able to see a project to see its board.');
  }


/* -(  PhorgeExtendedPolicyInterface  )--------------------------------- */


  public function getExtendedPolicy($capability, PhorgeUser $viewer) {
    return array(
      array($this->getProject(), $capability),
    );
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
    $this->delete();
    $this->saveTransaction();
  }

}
