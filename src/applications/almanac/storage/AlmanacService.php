<?php

final class AlmanacService
  extends AlmanacDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeProjectInterface,
    AlmanacPropertyInterface,
    PhorgeDestructibleInterface,
    PhorgeNgramsInterface,
    PhorgeConduitResultInterface,
    PhorgeExtendedPolicyInterface {

  protected $name;
  protected $nameIndex;
  protected $viewPolicy;
  protected $editPolicy;
  protected $serviceType;

  private $almanacProperties = self::ATTACHABLE;
  private $bindings = self::ATTACHABLE;
  private $activeBindings = self::ATTACHABLE;
  private $serviceImplementation = self::ATTACHABLE;

  public static function initializeNewService($type) {
    $type_map = AlmanacServiceType::getAllServiceTypes();

    $implementation = idx($type_map, $type);
    if (!$implementation) {
      throw new Exception(
        pht(
          'No Almanac service type "%s" exists!',
          $type));
    }

    return id(new AlmanacService())
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->setEditPolicy(PhorgePolicies::POLICY_ADMIN)
      ->attachAlmanacProperties(array())
      ->setServiceType($type)
      ->attachServiceImplementation($implementation);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text128',
        'nameIndex' => 'bytes12',
        'serviceType' => 'text64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_name' => array(
          'columns' => array('nameIndex'),
          'unique' => true,
        ),
        'key_nametext' => array(
          'columns' => array('name'),
        ),
        'key_servicetype' => array(
          'columns' => array('serviceType'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return AlmanacServicePHIDType::TYPECONST;
  }

  public function save() {
    AlmanacNames::validateName($this->getName());

    $this->nameIndex = PhorgeHash::digestForIndex($this->getName());

    return parent::save();
  }

  public function getURI() {
    return '/almanac/service/view/'.$this->getName().'/';
  }

  public function getBindings() {
    return $this->assertAttached($this->bindings);
  }

  public function getActiveBindings() {
    return $this->assertAttached($this->activeBindings);
  }

  public function attachBindings(array $bindings) {
    $active_bindings = array();
    foreach ($bindings as $key => $binding) {
      // Filter out disabled bindings.
      if ($binding->getIsDisabled()) {
        continue;
      }

      // Filter out bindings to disabled devices.
      if ($binding->getDevice()->isDisabled()) {
        continue;
      }

      $active_bindings[$key] = $binding;
    }

    $this->attachActiveBindings($active_bindings);

    $this->bindings = $bindings;
    return $this;
  }

  public function attachActiveBindings(array $bindings) {
    $this->activeBindings = $bindings;
    return $this;
  }

  public function getServiceImplementation() {
    return $this->assertAttached($this->serviceImplementation);
  }

  public function attachServiceImplementation(AlmanacServiceType $type) {
    $this->serviceImplementation = $type;
    return $this;
  }

  public function isClusterService() {
    return $this->getServiceImplementation()->isClusterServiceType();
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
    return $this->getServiceImplementation()->getFieldSpecifications();
  }

  public function getBindingFieldSpecifications(AlmanacBinding $binding) {
    $impl = $this->getServiceImplementation();
    return $impl->getBindingFieldSpecifications($binding);
  }

  public function newAlmanacPropertyEditEngine() {
    return new AlmanacServicePropertyEditEngine();
  }

  public function getAlmanacPropertySetTransactionType() {
    return AlmanacServiceSetPropertyTransaction::TRANSACTIONTYPE;
  }

  public function getAlmanacPropertyDeleteTransactionType() {
    return AlmanacServiceDeletePropertyTransaction::TRANSACTIONTYPE;
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


/* -(  PhorgeExtendedPolicyInterface  )--------------------------------- */


  public function getExtendedPolicy($capability, PhorgeUser $viewer) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_EDIT:
        if ($this->isClusterService()) {
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
    return new AlmanacServiceEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new AlmanacServiceTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $bindings = id(new AlmanacBindingQuery())
      ->setViewer($engine->getViewer())
      ->withServicePHIDs(array($this->getPHID()))
      ->execute();
    foreach ($bindings as $binding) {
      $engine->destroyObject($binding);
    }

    $this->delete();
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new AlmanacServiceNameNgrams())
        ->setValue($this->getName()),
    );
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the service.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('serviceType')
        ->setType('string')
        ->setDescription(pht('The service type constant.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
      'serviceType' => $this->getServiceType(),
    );
  }

  public function getConduitSearchAttachments() {
    return array(
      id(new AlmanacPropertiesSearchEngineAttachment())
        ->setAttachmentKey('properties'),
      id(new AlmanacBindingsSearchEngineAttachment())
        ->setAttachmentKey('bindings'),
      id(new AlmanacBindingsSearchEngineAttachment())
        ->setIsActive(true)
        ->setAttachmentKey('activeBindings'),
    );
  }

}
