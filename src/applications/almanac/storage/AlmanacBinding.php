<?php

final class AlmanacBinding
  extends AlmanacDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    AlmanacPropertyInterface,
    PhorgeDestructibleInterface,
    PhorgeExtendedPolicyInterface,
    PhorgeConduitResultInterface {

  protected $servicePHID;
  protected $devicePHID;
  protected $interfacePHID;
  protected $isDisabled;

  private $service = self::ATTACHABLE;
  private $device = self::ATTACHABLE;
  private $interface = self::ATTACHABLE;
  private $almanacProperties = self::ATTACHABLE;

  public static function initializeNewBinding(AlmanacService $service) {
    return id(new AlmanacBinding())
      ->setServicePHID($service->getPHID())
      ->attachService($service)
      ->attachAlmanacProperties(array())
      ->setIsDisabled(0);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'isDisabled' => 'bool',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_service' => array(
          'columns' => array('servicePHID', 'interfacePHID'),
          'unique' => true,
        ),
        'key_device' => array(
          'columns' => array('devicePHID'),
        ),
        'key_interface' => array(
          'columns' => array('interfacePHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return AlmanacBindingPHIDType::TYPECONST;
  }

  public function getName() {
    return pht('Binding %s', $this->getID());
  }

  public function getURI() {
    return urisprintf(
      '/almanac/binding/%s/',
      $this->getID());
  }

  public function getService() {
    return $this->assertAttached($this->service);
  }

  public function attachService(AlmanacService $service) {
    $this->service = $service;
    return $this;
  }

  public function getDevice() {
    return $this->assertAttached($this->device);
  }

  public function attachDevice(AlmanacDevice $device) {
    $this->device = $device;
    return $this;
  }

  public function hasInterface() {
    return ($this->interface !== self::ATTACHABLE);
  }

  public function getInterface() {
    return $this->assertAttached($this->interface);
  }

  public function attachInterface(AlmanacInterface $interface) {
    $this->interface = $interface;
    return $this;
  }


/* -(  AlmanacPropertyInterface  )------------------------------------------- */


  public function attachAlmanacProperties(array $properties) {
    assert_instances_of($properties, 'AlmanacProperty');
    $this->almanacProperties = mpull($properties, null, 'getFieldName');
    return $this;
  }

  public function getAlmanacProperties() {
    return $this->assertAttached($this->almanacProperties);
  }

  public function hasAlmanacProperty($key) {
    $this->assertAttached($this->almanacProperties);
    return isset($this->almanacProperties[$key]);
  }

  public function getAlmanacProperty($key) {
    return $this->assertAttachedKey($this->almanacProperties, $key);
  }

  public function getAlmanacPropertyValue($key, $default = null) {
    if ($this->hasAlmanacProperty($key)) {
      return $this->getAlmanacProperty($key)->getFieldValue();
    } else {
      return $default;
    }
  }

  public function getAlmanacPropertyFieldSpecifications() {
    return $this->getService()->getBindingFieldSpecifications($this);
  }

  public function newAlmanacPropertyEditEngine() {
    return new AlmanacBindingPropertyEditEngine();
  }

  public function getAlmanacPropertySetTransactionType() {
    return AlmanacBindingSetPropertyTransaction::TRANSACTIONTYPE;
  }

  public function getAlmanacPropertyDeleteTransactionType() {
    return AlmanacBindingDeletePropertyTransaction::TRANSACTIONTYPE;
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return $this->getService()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return $this->getService()->hasAutomaticCapability($capability, $viewer);
  }

  public function describeAutomaticCapability($capability) {
    $notes = array(
      pht('A binding inherits the policies of its service.'),
      pht(
        'To view a binding, you must also be able to view its device and '.
        'interface.'),
    );

    return $notes;
  }


/* -(  PhorgeExtendedPolicyInterface  )--------------------------------- */


  public function getExtendedPolicy($capability, PhorgeUser $viewer) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_EDIT:
        if ($this->getService()->isClusterService()) {
          return array(
            array(
              new PhorgeAlmanacApplication(),
              AlmanacManageClusterServicesCapability::CAPABILITY,
            ),
          );
        }
        break;
    }

    return array();
  }

/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new AlmanacBindingEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new AlmanacBindingTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->delete();
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('servicePHID')
        ->setType('phid')
        ->setDescription(pht('The bound service.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('devicePHID')
        ->setType('phid')
        ->setDescription(pht('The device the service is bound to.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('interfacePHID')
        ->setType('phid')
        ->setDescription(pht('The interface the service is bound to.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('disabled')
        ->setType('bool')
        ->setDescription(pht('Interface status.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'servicePHID' => $this->getServicePHID(),
      'devicePHID' => $this->getDevicePHID(),
      'interfacePHID' => $this->getInterfacePHID(),
      'disabled' => (bool)$this->getIsDisabled(),
    );
  }

  public function getConduitSearchAttachments() {
    return array(
      id(new AlmanacPropertiesSearchEngineAttachment())
        ->setAttachmentKey('properties'),
    );
  }

}
