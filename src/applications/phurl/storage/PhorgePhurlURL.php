<?php

final class PhorgePhurlURL extends PhorgePhurlDAO
  implements PhorgePolicyInterface,
  PhorgeProjectInterface,
  PhorgeApplicationTransactionInterface,
  PhorgeSubscribableInterface,
  PhorgeTokenReceiverInterface,
  PhorgeDestructibleInterface,
  PhorgeMentionableInterface,
  PhorgeFlaggableInterface,
  PhorgeSpacesInterface,
  PhorgeConduitResultInterface,
  PhorgeNgramsInterface {

  protected $name;
  protected $alias;
  protected $longURL;
  protected $description;

  protected $viewPolicy;
  protected $editPolicy;

  protected $authorPHID;
  protected $spacePHID;

  protected $mailKey;

  const DEFAULT_ICON = 'fa-compress';

  public static function initializeNewPhurlURL(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgePhurlApplication'))
      ->executeOne();

    return id(new PhorgePhurlURL())
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy(PhorgePolicies::getMostOpenPolicy())
      ->setEditPolicy($actor->getPHID())
      ->setSpacePHID($actor->getDefaultSpacePHID());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text',
        'alias' => 'sort64?',
        'longURL' => 'text',
        'description' => 'text',
        'mailKey' => 'bytes20',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_instance' => array(
          'columns' => array('alias'),
          'unique' => true,
        ),
        'key_author' => array(
          'columns' => array('authorPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePhurlURLPHIDType::TYPECONST);
  }

  public function getMonogram() {
    return 'U'.$this->getID();
  }

  public function getURI() {
    $uri = '/'.$this->getMonogram();
    return $uri;
  }

  public function isValid() {
    $allowed_protocols = PhorgeEnv::getEnvConfig('uri.allowed-protocols');
    $uri = new PhutilURI($this->getLongURL());

    return isset($allowed_protocols[$uri->getProtocol()]);
  }

  public function getDisplayName() {
    if ($this->getName()) {
      return $this->getName();
    } else {
      return $this->getLongURL();
    }
  }

  public function getRedirectURI() {
    if (strlen($this->getAlias())) {
      $path = '/u/'.$this->getAlias();
    } else {
      $path = '/u/'.$this->getID();
    }
    $domain = PhorgeEnv::getEnvConfig('phurl.short-uri');
    if (!$domain) {
      $domain = PhorgeEnv::getEnvConfig('phorge.base-uri');
    }

    $uri = new PhutilURI($domain);
    $uri->setPath($path);
    return (string)$uri;
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
    $user_phid = $this->getAuthorPHID();
    if ($user_phid) {
      $viewer_phid = $viewer->getPHID();
      if ($viewer_phid == $user_phid) {
        return true;
      }
    }

    return false;
  }

  public function describeAutomaticCapability($capability) {
    return pht('The owner of a URL can always view and edit it.');
  }

/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgePhurlURLEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgePhurlURLTransaction();
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($phid == $this->getAuthorPHID());
  }


/* -(  PhorgeTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array($this->getAuthorPHID());
  }

/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
    $this->delete();
    $this->saveTransaction();
  }

/* -(  PhorgeSpacesInterface  )----------------------------------------- */


  public function getSpacePHID() {
    return $this->spacePHID;
  }

/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('URL name.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('alias')
        ->setType('string')
        ->setDescription(pht('The alias for the URL.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('longurl')
        ->setType('string')
        ->setDescription(pht('The pre-shortened URL.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('description')
        ->setType('string')
        ->setDescription(pht('A description of the URL.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
      'alias' => $this->getAlias(),
      'description' => $this->getDescription(),
      'urls' => array(
        'long' => $this->getLongURL(),
        'short' => $this->getRedirectURI(),
      ),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

/* -(  PhorgeNgramInterface  )------------------------------------------ */


  public function newNgrams() {
    return array(
      id(new PhorgePhurlURLNameNgrams())
        ->setValue($this->getName()),
    );
  }

}
