<?php

final class PhorgeBadgesBadge extends PhorgeBadgesDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeSubscribableInterface,
    PhorgeFlaggableInterface,
    PhorgeDestructibleInterface,
    PhorgeConduitResultInterface,
    PhorgeNgramsInterface {

  protected $name;
  protected $flavor;
  protected $description;
  protected $icon;
  protected $quality;
  protected $mailKey;
  protected $editPolicy;
  protected $status;
  protected $creatorPHID;

  const STATUS_ACTIVE = 'open';
  const STATUS_ARCHIVED = 'closed';

  const DEFAULT_ICON = 'fa-star';

  public static function getStatusNameMap() {
    return array(
      self::STATUS_ACTIVE => pht('Active'),
      self::STATUS_ARCHIVED => pht('Archived'),
    );
  }

  public static function initializeNewBadge(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeBadgesApplication'))
      ->executeOne();

    $view_policy = PhorgePolicies::getMostOpenPolicy();

    $edit_policy =
      $app->getPolicy(PhorgeBadgesDefaultEditCapability::CAPABILITY);

    return id(new PhorgeBadgesBadge())
      ->setIcon(self::DEFAULT_ICON)
      ->setQuality(PhorgeBadgesQuality::DEFAULT_QUALITY)
      ->setCreatorPHID($actor->getPHID())
      ->setEditPolicy($edit_policy)
      ->setFlavor('')
      ->setDescription('')
      ->setStatus(self::STATUS_ACTIVE);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort255',
        'flavor' => 'text255',
        'description' => 'text',
        'icon' => 'text255',
        'quality' => 'uint32',
        'status' => 'text32',
        'mailKey' => 'bytes20',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_creator' => array(
          'columns' => array('creatorPHID', 'dateModified'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return
      PhorgePHID::generateNewPHID(PhorgeBadgesPHIDType::TYPECONST);
  }

  public function isArchived() {
    return ($this->getStatus() == self::STATUS_ARCHIVED);
  }

  public function getViewURI() {
    return '/badges/view/'.$this->getID().'/';
  }

  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
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
        return PhorgePolicies::getMostOpenPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeBadgesEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeBadgesTransaction();
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return false;
  }



/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $awards = id(new PhorgeBadgesAwardQuery())
      ->setViewer($engine->getViewer())
      ->withBadgePHIDs(array($this->getPHID()))
      ->execute();

    foreach ($awards as $award) {
      $engine->destroyObject($award);
    }

    $this->openTransaction();
      $this->delete();
    $this->saveTransaction();
  }

/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the badge.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('creatorPHID')
        ->setType('phid')
        ->setDescription(pht('User PHID of the creator.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('status')
        ->setType('string')
        ->setDescription(pht('Active or archived status of the badge.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
      'creatorPHID' => $this->getCreatorPHID(),
      'status' => $this->getStatus(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

/* -(  PhorgeNgramInterface  )------------------------------------------ */


  public function newNgrams() {
    return array(
      id(new PhorgeBadgesBadgeNameNgrams())
        ->setValue($this->getName()),
    );
  }

}
