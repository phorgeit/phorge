<?php

final class FundInitiative extends FundDAO
  implements
    PhorgePolicyInterface,
    PhorgeProjectInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeSubscribableInterface,
    PhorgeMentionableInterface,
    PhorgeFlaggableInterface,
    PhorgeTokenReceiverInterface,
    PhorgeDestructibleInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface {

  protected $name;
  protected $ownerPHID;
  protected $merchantPHID;
  protected $description;
  protected $risks;
  protected $viewPolicy;
  protected $editPolicy;
  protected $status;
  protected $totalAsCurrency;

  private $projectPHIDs = self::ATTACHABLE;

  const STATUS_OPEN = 'open';
  const STATUS_CLOSED = 'closed';

  public static function getStatusNameMap() {
    return array(
      self::STATUS_OPEN => pht('Open'),
      self::STATUS_CLOSED => pht('Closed'),
    );
  }

  public static function initializeNewInitiative(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeFundApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(FundDefaultViewCapability::CAPABILITY);

    return id(new FundInitiative())
      ->setOwnerPHID($actor->getPHID())
      ->setViewPolicy($view_policy)
      ->setEditPolicy($actor->getPHID())
      ->setStatus(self::STATUS_OPEN)
      ->setTotalAsCurrency(PhortuneCurrency::newEmptyCurrency());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text255',
        'description' => 'text',
        'risks' => 'text',
        'status' => 'text32',
        'merchantPHID' => 'phid?',
        'totalAsCurrency' => 'text64',
      ),
      self::CONFIG_APPLICATION_SERIALIZERS => array(
        'totalAsCurrency' => new PhortuneCurrencySerializer(),
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_status' => array(
          'columns' => array('status'),
        ),
        'key_owner' => array(
          'columns' => array('ownerPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return FundInitiativePHIDType::TYPECONST;
  }

  public function getMonogram() {
    return 'I'.$this->getID();
  }

  public function getViewURI() {
    return '/'.$this->getMonogram();
  }

  public function getProjectPHIDs() {
    return $this->assertAttached($this->projectPHIDs);
  }

  public function attachProjectPHIDs(array $phids) {
    $this->projectPHIDs = $phids;
    return $this;
  }

  public function isClosed() {
    return ($this->getStatus() == self::STATUS_CLOSED);
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
    if ($viewer->getPHID() == $this->getOwnerPHID()) {
      return true;
    }

    if ($capability == PhorgePolicyCapability::CAN_VIEW) {
      $can_merchant = PhortuneMerchantQuery::canViewersEditMerchants(
        array($viewer->getPHID()),
        array($this->getMerchantPHID()));

      if ($can_merchant) {
        return true;
      }
    }

    return false;
  }

  public function describeAutomaticCapability($capability) {
    return pht('The owner of an initiative can always view and edit it.');
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new FundInitiativeEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new FundInitiativeTransaction();
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($phid == $this->getOwnerPHID());
  }


/* -(  PhorgeTokenRecevierInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array(
      $this->getOwnerPHID(),
    );
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
      $this->delete();
    $this->saveTransaction();
  }


/* -(  PhorgeFulltextInterface  )--------------------------------------- */


  public function newFulltextEngine() {
    return new FundInitiativeFulltextEngine();
  }


/* -(  PhorgeFerretInterface  )----------------------------------------- */


  public function newFerretEngine() {
    return new FundInitiativeFerretEngine();
  }

}
