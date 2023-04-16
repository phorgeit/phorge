<?php

final class PhorgeCalendarImportLog
  extends PhorgeCalendarDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  protected $importPHID;
  protected $parameters = array();

  private $import = self::ATTACHABLE;
  private $logType = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_SERIALIZATION => array(
        'parameters' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_import' => array(
          'columns' => array('importPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getParameter($key, $default = null) {
    return idx($this->parameters, $key, $default);
  }

  public function setParameter($key, $value) {
    $this->parameters[$key] = $value;
    return $this;
  }

  public function getImport() {
    return $this->assertAttached($this->import);
  }

  public function attachImport(PhorgeCalendarImport $import) {
    $this->import = $import;
    return $this;
  }

  public function getDisplayIcon(PhorgeUser $viewer) {
    return $this->getLogType()->getDisplayIcon($viewer, $this);
  }

  public function getDisplayColor(PhorgeUser $viewer) {
    return $this->getLogType()->getDisplayColor($viewer, $this);
  }

  public function getDisplayType(PhorgeUser $viewer) {
    return $this->getLogType()->getDisplayType($viewer, $this);
  }

  public function getDisplayDescription(PhorgeUser $viewer) {
    return $this->getLogType()->getDisplayDescription($viewer, $this);
  }

  public function getLogType() {
    return $this->assertAttached($this->logType);
  }

  public function attachLogType(PhorgeCalendarImportLogType $type) {
    $this->logType = $type;
    return $this;
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return PhorgePolicies::getMostOpenPolicy();
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $viewer = $engine->getViewer();
    $this->delete();
  }

}
