<?php

final class PhorgeCountdown extends PhorgeCountdownDAO
  implements
    PhorgePolicyInterface,
    PhorgeFlaggableInterface,
    PhorgeSubscribableInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeTokenReceiverInterface,
    PhorgeSpacesInterface,
    PhorgeProjectInterface,
    PhorgeDestructibleInterface,
    PhorgeConduitResultInterface {

  protected $title;
  protected $authorPHID;
  protected $epoch;
  protected $description;
  protected $viewPolicy;
  protected $editPolicy;
  protected $mailKey;
  protected $spacePHID;

  public static function initializeNewCountdown(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeCountdownApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(
      PhorgeCountdownDefaultViewCapability::CAPABILITY);

    $edit_policy = $app->getPolicy(
      PhorgeCountdownDefaultEditCapability::CAPABILITY);

    return id(new PhorgeCountdown())
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy)
      ->setSpacePHID($actor->getDefaultSpacePHID());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'title' => 'text255',
        'description' => 'text',
        'mailKey' => 'bytes20',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_epoch' => array(
          'columns' => array('epoch'),
        ),
        'key_author' => array(
          'columns' => array('authorPHID', 'epoch'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeCountdownCountdownPHIDType::TYPECONST);
  }

  public function getMonogram() {
    return 'C'.$this->getID();
  }

  public function getURI() {
    return '/'.$this->getMonogram();
  }

  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($phid == $this->getAuthorPHID());
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeCountdownEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeCountdownTransaction();
  }


/* -(  PhorgeTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array($this->getAuthorPHID());
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

/* -( PhorgeSpacesInterface )------------------------------------------- */


  public function getSpacePHID() {
    return $this->spacePHID;
  }

/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
      PhorgeDestructionEngine $engine) {

    $this->openTransaction();
    $this->delete();
    $this->saveTransaction();
  }

/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('title')
        ->setType('string')
        ->setDescription(pht('The title of the countdown.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('description')
        ->setType('remarkup')
        ->setDescription(pht('The description of the countdown.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('epoch')
        ->setType('epoch')
        ->setDescription(pht('The end date of the countdown.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'title' => $this->getTitle(),
      'description' => array(
        'raw' => $this->getDescription(),
      ),
      'epoch' => (int)$this->getEpoch(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

}
