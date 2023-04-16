<?php

final class PhorgeMetaMTAApplicationEmail
  extends PhorgeMetaMTADAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeDestructibleInterface,
    PhorgeSpacesInterface {

  protected $applicationPHID;
  protected $address;
  protected $configData;
  protected $spacePHID;

  private $application = self::ATTACHABLE;

  const CONFIG_DEFAULT_AUTHOR = 'config:default:author';

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'configData' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'address' => 'sort128',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_address' => array(
          'columns' => array('address'),
          'unique' => true,
        ),
        'key_application' => array(
          'columns' => array('applicationPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeMetaMTAApplicationEmailPHIDType::TYPECONST);
  }

  public static function initializeNewAppEmail(PhorgeUser $actor) {
    return id(new PhorgeMetaMTAApplicationEmail())
      ->setSpacePHID($actor->getDefaultSpacePHID())
      ->setConfigData(array());
  }

  public function attachApplication(PhorgeApplication $app) {
    $this->application = $app;
    return $this;
  }

  public function getApplication() {
    return self::assertAttached($this->application);
  }

  public function setConfigValue($key, $value) {
    $this->configData[$key] = $value;
    return $this;
  }

  public function getConfigValue($key, $default = null) {
    return idx($this->configData, $key, $default);
  }

  public function getDefaultAuthorPHID() {
    return $this->getConfigValue(self::CONFIG_DEFAULT_AUTHOR);
  }

  public function getInUseMessage() {
    $applications = PhorgeApplication::getAllApplications();
    $applications = mpull($applications, null, 'getPHID');
    $application = idx(
      $applications,
      $this->getApplicationPHID());
    if ($application) {
      $message = pht(
        'The address %s is configured to be used by the %s Application.',
        $this->getAddress(),
        $application->getName());
    } else {
      $message = pht(
        'The address %s is configured to be used by an application.',
        $this->getAddress());
    }

    return $message;
  }

  public function newAddress() {
    return new PhutilEmailAddress($this->getAddress());
  }

/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return $this->getApplication()->getPolicy($capability);
  }

  public function hasAutomaticCapability(
    $capability,
    PhorgeUser $viewer) {

    return $this->getApplication()->hasAutomaticCapability(
      $capability,
      $viewer);
  }

  public function describeAutomaticCapability($capability) {
    return $this->getApplication()->describeAutomaticCapability($capability);
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeMetaMTAApplicationEmailEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeMetaMTAApplicationEmailTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }


/* -(  PhorgeSpacesInterface  )----------------------------------------- */


  public function getSpacePHID() {
    return $this->spacePHID;
  }

}
