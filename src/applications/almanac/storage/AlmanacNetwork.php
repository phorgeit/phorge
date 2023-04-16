<?php

final class AlmanacNetwork
  extends AlmanacDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeDestructibleInterface,
    PhorgeNgramsInterface,
    PhorgeConduitResultInterface {

  protected $name;
  protected $viewPolicy;
  protected $editPolicy;

  public static function initializeNewNetwork() {
    return id(new AlmanacNetwork())
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->setEditPolicy(PhorgePolicies::POLICY_ADMIN);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort128',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_name' => array(
            'columns' => array('name'),
            'unique' => true,
          ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return AlmanacNetworkPHIDType::TYPECONST;
  }

  public function getURI() {
    return urisprintf(
      '/almanac/network/%s/',
      $this->getID());
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new AlmanacNetworkEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new AlmanacNetworkTransaction();
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


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $interfaces = id(new AlmanacInterfaceQuery())
      ->setViewer($engine->getViewer())
      ->withNetworkPHIDs(array($this->getPHID()))
      ->execute();

    foreach ($interfaces as $interface) {
      $engine->destroyObject($interface);
    }

    $this->delete();
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new AlmanacNetworkNameNgrams())
        ->setValue($this->getName()),
    );
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the network.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

}
