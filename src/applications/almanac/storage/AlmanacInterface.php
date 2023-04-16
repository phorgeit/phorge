<?php

final class AlmanacInterface
  extends AlmanacDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface,
    PhorgeExtendedPolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeConduitResultInterface {

  protected $devicePHID;
  protected $networkPHID;
  protected $address;
  protected $port;

  private $device = self::ATTACHABLE;
  private $network = self::ATTACHABLE;

  public static function initializeNewInterface() {
    return id(new AlmanacInterface());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'address' => 'text64',
        'port' => 'uint32',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_location' => array(
          'columns' => array('networkPHID', 'address', 'port'),
        ),
        'key_device' => array(
          'columns' => array('devicePHID'),
        ),
        'key_unique' => array(
          'columns' => array('devicePHID', 'networkPHID', 'address', 'port'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      AlmanacInterfacePHIDType::TYPECONST);
  }

  public function getDevice() {
    return $this->assertAttached($this->device);
  }

  public function attachDevice(AlmanacDevice $device) {
    $this->device = $device;
    return $this;
  }

  public function getNetwork() {
    return $this->assertAttached($this->network);
  }

  public function attachNetwork(AlmanacNetwork $network) {
    $this->network = $network;
    return $this;
  }

  public function toAddress() {
    return AlmanacAddress::newFromParts(
      $this->getNetworkPHID(),
      $this->getAddress(),
      $this->getPort());
  }

  public function getAddressHash() {
    return $this->toAddress()->toHash();
  }

  public function renderDisplayAddress() {
    return $this->getAddress().':'.$this->getPort();
  }

  public function loadIsInUse() {
    $binding = id(new AlmanacBindingQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withInterfacePHIDs(array($this->getPHID()))
      ->setLimit(1)
      ->executeOne();

    return (bool)$binding;
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return $this->getDevice()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return $this->getDevice()->hasAutomaticCapability($capability, $viewer);
  }

  public function describeAutomaticCapability($capability) {
    $notes = array(
      pht('An interface inherits the policies of the device it belongs to.'),
      pht(
        'You must be able to view the network an interface resides on to '.
        'view the interface.'),
    );

    return $notes;
  }


/* -(  PhorgeExtendedPolicyInterface  )--------------------------------- */


  public function getExtendedPolicy($capability, PhorgeUser $viewer) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_EDIT:
        if ($this->getDevice()->isClusterDevice()) {
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


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $bindings = id(new AlmanacBindingQuery())
      ->setViewer($engine->getViewer())
      ->withInterfacePHIDs(array($this->getPHID()))
      ->execute();
    foreach ($bindings as $binding) {
      $engine->destroyObject($binding);
    }

    $this->delete();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new AlmanacInterfaceEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new AlmanacInterfaceTransaction();
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('devicePHID')
        ->setType('phid')
        ->setDescription(pht('The device the interface is on.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('networkPHID')
        ->setType('phid')
        ->setDescription(pht('The network the interface is part of.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('address')
        ->setType('string')
        ->setDescription(pht('The address of the interface.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('port')
        ->setType('int')
        ->setDescription(pht('The port number of the interface.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'devicePHID' => $this->getDevicePHID(),
      'networkPHID' => $this->getNetworkPHID(),
      'address' => (string)$this->getAddress(),
      'port' => (int)$this->getPort(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

}
