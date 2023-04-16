<?php


final class PhorgeAuthFactorConfig
  extends PhorgeAuthDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  protected $userPHID;
  protected $factorProviderPHID;
  protected $factorName;
  protected $factorSecret;
  protected $properties = array();

  private $sessionEngine;
  private $factorProvider = self::ATTACHABLE;
  private $mfaSyncToken;

  protected function getConfiguration() {
    return array(
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'factorName' => 'text',
        'factorSecret' => 'text',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_user' => array(
          'columns' => array('userPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeAuthAuthFactorPHIDType::TYPECONST;
  }

  public function attachFactorProvider(
    PhorgeAuthFactorProvider $provider) {
    $this->factorProvider = $provider;
    return $this;
  }

  public function getFactorProvider() {
    return $this->assertAttached($this->factorProvider);
  }

  public function setSessionEngine(PhorgeAuthSessionEngine $engine) {
    $this->sessionEngine = $engine;
    return $this;
  }

  public function getSessionEngine() {
    if (!$this->sessionEngine) {
      throw new PhutilInvalidStateException('setSessionEngine');
    }

    return $this->sessionEngine;
  }

  public function setMFASyncToken(PhorgeAuthTemporaryToken $token) {
    $this->mfaSyncToken = $token;
    return $this;
  }

  public function getMFASyncToken() {
    return $this->mfaSyncToken;
  }

  public function getAuthFactorConfigProperty($key, $default = null) {
    return idx($this->properties, $key, $default);
  }

  public function setAuthFactorConfigProperty($key, $value) {
    $this->properties[$key] = $value;
    return $this;
  }

  public function newSortVector() {
    return id(new PhutilSortVector())
      ->addInt($this->getFactorProvider()->newStatus()->getOrder())
      ->addInt($this->getID());
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return $this->getUserPHID();
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $user = id(new PhorgePeopleQuery())
      ->setViewer($engine->getViewer())
      ->withPHIDs(array($this->getUserPHID()))
      ->executeOne();

    $this->delete();

    if ($user) {
      $user->updateMultiFactorEnrollment();
    }
  }

}
