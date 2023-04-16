<?php

final class AlmanacNamespace
  extends AlmanacDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeProjectInterface,
    PhorgeDestructibleInterface,
    PhorgeNgramsInterface,
    PhorgeConduitResultInterface {

  protected $name;
  protected $nameIndex;
  protected $viewPolicy;
  protected $editPolicy;

  public static function initializeNewNamespace() {
    return id(new self())
      ->setViewPolicy(PhorgePolicies::POLICY_USER)
      ->setEditPolicy(PhorgePolicies::POLICY_ADMIN);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text128',
        'nameIndex' => 'bytes12',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_nameindex' => array(
          'columns' => array('nameIndex'),
          'unique' => true,
        ),
        'key_name' => array(
          'columns' => array('name'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return AlmanacNamespacePHIDType::TYPECONST;
  }

  public function save() {
    AlmanacNames::validateName($this->getName());

    $this->nameIndex = PhorgeHash::digestForIndex($this->getName());

    return parent::save();
  }

  public function getURI() {
    return urisprintf(
      '/almanac/namespace/view/%s/',
      $this->getName());
  }

  public function getNameLength() {
    return strlen($this->getName());
  }

  /**
   * Load the namespace which prevents use of an Almanac name, if one exists.
   */
  public static function loadRestrictedNamespace(
    PhorgeUser $viewer,
    $name) {

    // For a name like "x.y.z", produce a list of controlling namespaces like
    // ("z", "y.x", "x.y.z").
    $names = array();
    $parts = explode('.', $name);
    for ($ii = 0; $ii < count($parts); $ii++) {
      $names[] = implode('.', array_slice($parts, -($ii + 1)));
    }

    // Load all the possible controlling namespaces.
    $namespaces = id(new AlmanacNamespaceQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withNames($names)
      ->execute();
    if (!$namespaces) {
      return null;
    }

    // Find the "nearest" (longest) namespace that exists. If both
    // "sub.domain.com" and "domain.com" exist, we only care about the policy
    // on the former.
    $namespaces = msort($namespaces, 'getNameLength');
    $namespace = last($namespaces);

    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $namespace,
      PhorgePolicyCapability::CAN_EDIT);
    if ($can_edit) {
      return null;
    }

    return $namespace;
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
    return new AlmanacNamespaceEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new AlmanacNamespaceTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new AlmanacNamespaceNameNgrams())
        ->setValue($this->getName()),
    );
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the namespace.')),
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
