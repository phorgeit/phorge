<?php

final class HarbormasterBuildStep extends HarbormasterDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeCustomFieldInterface,
    PhorgeConduitResultInterface {

  protected $name;
  protected $description;
  protected $buildPlanPHID;
  protected $className;
  protected $details = array();
  protected $sequence = 0;
  protected $stepAutoKey;

  private $buildPlan = self::ATTACHABLE;
  private $customFields = self::ATTACHABLE;
  private $implementation;

  public static function initializeNewStep(PhorgeUser $actor) {
    return id(new HarbormasterBuildStep())
      ->setName('')
      ->setDescription('');
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'details' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'className' => 'text255',
        'sequence' => 'uint32',
        'description' => 'text',

        // T6203/NULLABILITY
        // This should not be nullable. Current `null` values indicate steps
        // which predated editable names. These should be backfilled with
        // default names, then the code for handling `null` should be removed.
        'name' => 'text255?',
        'stepAutoKey' => 'text32?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_plan' => array(
          'columns' => array('buildPlanPHID'),
        ),
        'key_stepautokey' => array(
          'columns' => array('buildPlanPHID', 'stepAutoKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      HarbormasterBuildStepPHIDType::TYPECONST);
  }

  public function attachBuildPlan(HarbormasterBuildPlan $plan) {
    $this->buildPlan = $plan;
    return $this;
  }

  public function getBuildPlan() {
    return $this->assertAttached($this->buildPlan);
  }

  public function getDetail($key, $default = null) {
    return idx($this->details, $key, $default);
  }

  public function setDetail($key, $value) {
    $this->details[$key] = $value;
    return $this;
  }

  public function getName() {
    if (strlen($this->name)) {
      return $this->name;
    }

    return $this->getStepImplementation()->getName();
  }

  public function getStepImplementation() {
    if ($this->implementation === null) {
      $obj = HarbormasterBuildStepImplementation::requireImplementation(
        $this->className);
      $obj->loadSettings($this);
      $this->implementation = $obj;
    }

    return $this->implementation;
  }

  public function isAutostep() {
    return ($this->getStepAutoKey() !== null);
  }

  public function willStartBuild(
    PhorgeUser $viewer,
    HarbormasterBuildable $buildable,
    HarbormasterBuild $build,
    HarbormasterBuildPlan $plan) {
    return $this->getStepImplementation()->willStartBuild(
      $viewer,
      $buildable,
      $build,
      $plan,
      $this);
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new HarbormasterBuildStepEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new HarbormasterBuildStepTransaction();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return $this->getBuildPlan()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return $this->getBuildPlan()->hasAutomaticCapability($capability, $viewer);
  }

  public function describeAutomaticCapability($capability) {
    return pht('A build step has the same policies as its build plan.');
  }


/* -(  PhorgeCustomFieldInterface  )------------------------------------ */


  public function getCustomFieldSpecificationForRole($role) {
    return array();
  }

  public function getCustomFieldBaseClass() {
    return 'HarbormasterBuildStepCustomField';
  }

  public function getCustomFields() {
    return $this->assertAttached($this->customFields);
  }

  public function attachCustomFields(PhorgeCustomFieldAttachment $fields) {
    $this->customFields = $fields;
    return $this;
  }

/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the build step.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('description')
        ->setType('remarkup')
        ->setDescription(pht('The build step description.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('buildPlanPHID')
        ->setType('phid')
        ->setDescription(
          pht(
            'The PHID of the build plan this build step belongs to.')),
    );
  }

  public function getFieldValuesForConduit() {
    // T6203: This can be removed once the field becomes non-nullable.
    $name = $this->getName();
    $name = phutil_string_cast($name);

    return array(
      'name' => $name,
      'description' => array(
        'raw' => $this->getDescription(),
      ),
      'buildPlanPHID' => $this->getBuildPlanPHID(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }


}
