<?php

final class PhorgeAuthFactorProvider
  extends PhorgeAuthDAO
  implements
     PhorgeApplicationTransactionInterface,
     PhorgePolicyInterface,
     PhorgeExtendedPolicyInterface,
     PhorgeEditEngineMFAInterface {

  protected $providerFactorKey;
  protected $name;
  protected $status;
  protected $properties = array();

  private $factor = self::ATTACHABLE;

  public static function initializeNewProvider(PhorgeAuthFactor $factor) {
    return id(new self())
      ->setProviderFactorKey($factor->getFactorKey())
      ->attachFactor($factor)
      ->setStatus(PhorgeAuthFactorProviderStatus::STATUS_ACTIVE);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_SERIALIZATION => array(
        'properties' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'providerFactorKey' => 'text64',
        'name' => 'text255',
        'status' => 'text32',
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeAuthAuthFactorProviderPHIDType::TYPECONST;
  }

  public function getURI() {
    return '/auth/mfa/'.$this->getID().'/';
  }

  public function getObjectName() {
    return pht('MFA Provider %d', $this->getID());
  }

  public function getAuthFactorProviderProperty($key, $default = null) {
    return idx($this->properties, $key, $default);
  }

  public function setAuthFactorProviderProperty($key, $value) {
    $this->properties[$key] = $value;
    return $this;
  }

  public function getEnrollMessage() {
    return $this->getAuthFactorProviderProperty('enroll-message');
  }

  public function setEnrollMessage($message) {
    return $this->setAuthFactorProviderProperty('enroll-message', $message);
  }

  public function attachFactor(PhorgeAuthFactor $factor) {
    $this->factor = $factor;
    return $this;
  }

  public function getFactor() {
    return $this->assertAttached($this->factor);
  }

  public function getDisplayName() {
    $name = $this->getName();
    if (strlen($name)) {
      return $name;
    }

    return $this->getFactor()->getFactorName();
  }

  public function newIconView() {
    return $this->getFactor()->newIconView();
  }

  public function getDisplayDescription() {
    return $this->getFactor()->getFactorDescription();
  }

  public function processAddFactorForm(
    AphrontFormView $form,
    AphrontRequest $request,
    PhorgeUser $user) {

    $factor = $this->getFactor();

    $config = $factor->processAddFactorForm($this, $form, $request, $user);
    if ($config) {
      $config->setFactorProviderPHID($this->getPHID());
    }

    return $config;
  }

  public function newSortVector() {
    $factor = $this->getFactor();

    return id(new PhutilSortVector())
      ->addInt($factor->getFactorOrder())
      ->addInt($this->getID());
  }

  public function getEnrollDescription(PhorgeUser $user) {
    return $this->getFactor()->getEnrollDescription($this, $user);
  }

  public function getEnrollButtonText(PhorgeUser $user) {
    return $this->getFactor()->getEnrollButtonText($this, $user);
  }

  public function newStatus() {
    $status_key = $this->getStatus();
    return PhorgeAuthFactorProviderStatus::newForStatus($status_key);
  }

  public function canCreateNewConfiguration(PhorgeUser $user) {
    return $this->getFactor()->canCreateNewConfiguration($this, $user);
  }

  public function getConfigurationCreateDescription(PhorgeUser $user) {
    return $this->getFactor()->getConfigurationCreateDescription($this, $user);
  }

  public function getConfigurationListDetails(
    PhorgeAuthFactorConfig $config,
    PhorgeUser $viewer) {
    return $this->getFactor()->getConfigurationListDetails(
      $config,
      $this,
      $viewer);
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeAuthFactorProviderEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeAuthFactorProviderTransaction();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return PhorgePolicies::getMostOpenPolicy();
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeExtendedPolicyInterface  )--------------------------------- */


  public function getExtendedPolicy($capability, PhorgeUser $viewer) {
    $extended = array();

    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        break;
      case PhorgePolicyCapability::CAN_EDIT:
        $extended[] = array(
          new PhorgeAuthApplication(),
          AuthManageProvidersCapability::CAPABILITY,
        );
        break;
    }

    return $extended;
  }


/* -(  PhorgeEditEngineMFAInterface  )---------------------------------- */


  public function newEditEngineMFAEngine() {
    return new PhorgeAuthFactorProviderMFAEngine();
  }

}
