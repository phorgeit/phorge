<?php

final class PhorgeCalendarImport
  extends PhorgeCalendarDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeDestructibleInterface {

  protected $name;
  protected $authorPHID;
  protected $viewPolicy;
  protected $editPolicy;
  protected $engineType;
  protected $parameters = array();
  protected $isDisabled = 0;
  protected $triggerPHID;
  protected $triggerFrequency;

  const FREQUENCY_ONCE = 'once';
  const FREQUENCY_HOURLY = 'hourly';
  const FREQUENCY_DAILY = 'daily';

  private $engine = self::ATTACHABLE;

  public static function initializeNewCalendarImport(
    PhorgeUser $actor,
    PhorgeCalendarImportEngine $engine) {
    return id(new self())
      ->setName('')
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy($actor->getPHID())
      ->setEditPolicy($actor->getPHID())
      ->setIsDisabled(0)
      ->setEngineType($engine->getImportEngineType())
      ->attachEngine($engine)
      ->setTriggerFrequency(self::FREQUENCY_ONCE);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'parameters' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text',
        'engineType' => 'text64',
        'isDisabled' => 'bool',
        'triggerPHID' => 'phid?',
        'triggerFrequency' => 'text64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_author' => array(
          'columns' => array('authorPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeCalendarImportPHIDType::TYPECONST;
  }

  public function getURI() {
    $id = $this->getID();
    return "/calendar/import/{$id}/";
  }

  public function attachEngine(PhorgeCalendarImportEngine $engine) {
    $this->engine = $engine;
    return $this;
  }

  public function getEngine() {
    return $this->assertAttached($this->engine);
  }

  public function getParameter($key, $default = null) {
    return idx($this->parameters, $key, $default);
  }

  public function setParameter($key, $value) {
    $this->parameters[$key] = $value;
    return $this;
  }

  public function getDisplayName() {
    $name = $this->getName();
    if (strlen($name)) {
      return $name;
    }

    return $this->getEngine()->getDisplayName($this);
  }

  public static function getTriggerFrequencyMap() {
    return array(
      self::FREQUENCY_ONCE => array(
        'name' => pht('No Automatic Updates'),
      ),
      self::FREQUENCY_HOURLY => array(
        'name' => pht('Update Hourly'),
      ),
      self::FREQUENCY_DAILY => array(
        'name' => pht('Update Daily'),
      ),
    );
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
    return new PhorgeCalendarImportEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeCalendarImportTransaction();
  }

  public function newLogMessage($type, array $parameters) {
    $parameters = array(
      'type' => $type,
    ) + $parameters;

    return id(new PhorgeCalendarImportLog())
      ->setImportPHID($this->getPHID())
      ->setParameters($parameters)
      ->save();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $viewer = $engine->getViewer();

    $this->openTransaction();

      $trigger_phid = $this->getTriggerPHID();
      if ($trigger_phid) {
        $trigger = id(new PhorgeWorkerTriggerQuery())
          ->setViewer($viewer)
          ->withPHIDs(array($trigger_phid))
          ->executeOne();
        if ($trigger) {
          $engine->destroyObject($trigger);
        }
      }

      $events = id(new PhorgeCalendarEventQuery())
        ->setViewer($viewer)
        ->withImportSourcePHIDs(array($this->getPHID()))
        ->execute();
      foreach ($events as $event) {
        $engine->destroyObject($event);
      }

      $logs = id(new PhorgeCalendarImportLogQuery())
        ->setViewer($viewer)
        ->withImportPHIDs(array($this->getPHID()))
        ->execute();
      foreach ($logs as $log) {
        $engine->destroyObject($log);
      }

      $this->delete();
    $this->saveTransaction();
  }

}
