<?php

/**
 * @task autoplan Autoplans
 */
final class HarbormasterBuildPlan extends HarbormasterDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeSubscribableInterface,
    PhorgeNgramsInterface,
    PhorgeConduitResultInterface,
    PhorgeProjectInterface,
    PhorgePolicyCodexInterface {

  protected $name;
  protected $planStatus;
  protected $planAutoKey;
  protected $viewPolicy;
  protected $editPolicy;
  protected $properties = array();

  const STATUS_ACTIVE   = 'active';
  const STATUS_DISABLED = 'disabled';

  private $buildSteps = self::ATTACHABLE;

  public static function initializeNewBuildPlan(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeHarbormasterApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(
      HarbormasterBuildPlanDefaultViewCapability::CAPABILITY);
    $edit_policy = $app->getPolicy(
      HarbormasterBuildPlanDefaultEditCapability::CAPABILITY);

    return id(new HarbormasterBuildPlan())
      ->setName('')
      ->setPlanStatus(self::STATUS_ACTIVE)
      ->attachBuildSteps(array())
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort128',
        'planStatus' => 'text32',
        'planAutoKey' => 'text32?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_status' => array(
          'columns' => array('planStatus'),
        ),
        'key_name' => array(
          'columns' => array('name'),
        ),
        'key_planautokey' => array(
          'columns' => array('planAutoKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      HarbormasterBuildPlanPHIDType::TYPECONST);
  }

  public function attachBuildSteps(array $steps) {
    assert_instances_of($steps, 'HarbormasterBuildStep');
    $this->buildSteps = $steps;
    return $this;
  }

  public function getBuildSteps() {
    return $this->assertAttached($this->buildSteps);
  }

  public function isDisabled() {
    return ($this->getPlanStatus() == self::STATUS_DISABLED);
  }

  public function getURI() {
    return urisprintf(
      '/harbormaster/plan/%s/',
      $this->getID());
  }

  public function getObjectName() {
    return pht('Plan %d', $this->getID());
  }

  public function getPlanProperty($key, $default = null) {
    return idx($this->properties, $key, $default);
  }

  public function setPlanProperty($key, $value) {
    $this->properties[$key] = $value;
    return $this;
  }


/* -(  Autoplans  )---------------------------------------------------------- */


  public function isAutoplan() {
    return ($this->getPlanAutoKey() !== null);
  }


  public function getAutoplan() {
    if (!$this->isAutoplan()) {
      return null;
    }

    return HarbormasterBuildAutoplan::getAutoplan($this->getPlanAutoKey());
  }


  public function canRunManually() {
    if ($this->isAutoplan()) {
      return false;
    }

    return true;
  }

  public function getName() {
    $autoplan = $this->getAutoplan();
    if ($autoplan) {
      return $autoplan->getAutoplanName();
    }

    return parent::getName();
  }

  public function hasRunCapability(PhorgeUser $viewer) {
    try {
      $this->assertHasRunCapability($viewer);
      return true;
    } catch (PhorgePolicyException $ex) {
      return false;
    }
  }

  public function canRunWithoutEditCapability() {
    $runnable = HarbormasterBuildPlanBehavior::BEHAVIOR_RUNNABLE;
    $if_viewable = HarbormasterBuildPlanBehavior::RUNNABLE_IF_VIEWABLE;

    $option = HarbormasterBuildPlanBehavior::getBehavior($runnable)
      ->getPlanOption($this);

    return ($option->getKey() === $if_viewable);
  }

  public function assertHasRunCapability(PhorgeUser $viewer) {
    if ($this->canRunWithoutEditCapability()) {
      $capability = PhorgePolicyCapability::CAN_VIEW;
    } else {
      $capability = PhorgePolicyCapability::CAN_EDIT;
    }

    PhorgePolicyFilter::requireCapability(
      $viewer,
      $this,
      $capability);
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return false;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new HarbormasterBuildPlanEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new HarbormasterBuildPlanTransaction();
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
        if ($this->isAutoplan()) {
          return PhorgePolicies::getMostOpenPolicy();
        }
        return $this->getViewPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        if ($this->isAutoplan()) {
          return PhorgePolicies::POLICY_NOONE;
        }
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

  public function describeAutomaticCapability($capability) {
    $messages = array();

    switch ($capability) {
      case PhorgePolicyCapability::CAN_EDIT:
        if ($this->isAutoplan()) {
          $messages[] = pht(
            'This is an autoplan (a builtin plan provided by an application) '.
            'so it can not be edited.');
        }
        break;
    }

    return $messages;
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new HarbormasterBuildPlanNameNgrams())
        ->setValue($this->getName()),
    );
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of this build plan.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('status')
        ->setType('map<string, wild>')
        ->setDescription(pht('The current status of this build plan.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('behaviors')
        ->setType('map<string, string>')
        ->setDescription(pht('Behavior configuration for the build plan.')),
    );
  }

  public function getFieldValuesForConduit() {
    $behavior_map = array();

    $behaviors = HarbormasterBuildPlanBehavior::newPlanBehaviors();
    foreach ($behaviors as $behavior) {
      $option = $behavior->getPlanOption($this);

      $behavior_map[$behavior->getKey()] = array(
        'value' => $option->getKey(),
      );
    }

    return array(
      'name' => $this->getName(),
      'status' => array(
        'value' => $this->getPlanStatus(),
      ),
      'behaviors' => $behavior_map,
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }


/* -(  PhorgePolicyCodexInterface  )------------------------------------ */


  public function newPolicyCodex() {
    return new HarbormasterBuildPlanPolicyCodex();
  }

}
