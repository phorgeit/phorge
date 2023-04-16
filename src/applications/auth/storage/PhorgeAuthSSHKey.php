<?php

final class PhorgeAuthSSHKey
  extends PhorgeAuthDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface,
    PhorgeApplicationTransactionInterface {

  protected $objectPHID;
  protected $name;
  protected $keyType;
  protected $keyIndex;
  protected $keyBody;
  protected $keyComment = '';
  protected $isTrusted = 0;
  protected $isActive;

  private $object = self::ATTACHABLE;

  public static function initializeNewSSHKey(
    PhorgeUser $viewer,
    PhorgeSSHPublicKeyInterface $object) {

    // You must be able to edit an object to create a new key on it.
    PhorgePolicyFilter::requireCapability(
      $viewer,
      $object,
      PhorgePolicyCapability::CAN_EDIT);

    $object_phid = $object->getPHID();

    return id(new self())
      ->setIsActive(1)
      ->setObjectPHID($object_phid)
      ->attachObject($object);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255',
        'keyType' => 'text255',
        'keyIndex' => 'bytes12',
        'keyBody' => 'text',
        'keyComment' => 'text255',
        'isTrusted' => 'bool',
        'isActive' => 'bool?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_object' => array(
          'columns' => array('objectPHID'),
        ),
        'key_active' => array(
          'columns' => array('isActive', 'objectPHID'),
        ),
        // NOTE: This unique key includes a nullable column, effectively
        // constraining uniqueness on active keys only.
        'key_activeunique' => array(
          'columns' => array('keyIndex', 'isActive'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function save() {
    $this->setKeyIndex($this->toPublicKey()->getHash());
    return parent::save();
  }

  public function toPublicKey() {
    return PhorgeAuthSSHPublicKey::newFromStoredKey($this);
  }

  public function getEntireKey() {
    $parts = array(
      $this->getKeyType(),
      $this->getKeyBody(),
      $this->getKeyComment(),
    );
    return trim(implode(' ', $parts));
  }

  public function getObject() {
    return $this->assertAttached($this->object);
  }

  public function attachObject(PhorgeSSHPublicKeyInterface $object) {
    $this->object = $object;
    return $this;
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeAuthSSHKeyPHIDType::TYPECONST);
  }

  public function getURI() {
    $id = $this->getID();
    return "/auth/sshkey/view/{$id}/";
  }

/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    if (!$this->getIsActive()) {
      if ($capability == PhorgePolicyCapability::CAN_EDIT) {
        return PhorgePolicies::POLICY_NOONE;
      }
    }

    return $this->getObject()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    if (!$this->getIsActive()) {
      return false;
    }

    return $this->getObject()->hasAutomaticCapability($capability, $viewer);
  }

  public function describeAutomaticCapability($capability) {
    if (!$this->getIsACtive()) {
      return pht(
        'Revoked SSH keys can not be edited or reinstated.');
    }

    return pht(
      'SSH keys inherit the policies of the user or object they authenticate.');
  }

/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
    $this->delete();
    $this->saveTransaction();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeAuthSSHKeyEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeAuthSSHKeyTransaction();
  }

}
