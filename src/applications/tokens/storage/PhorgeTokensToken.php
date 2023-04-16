<?php

final class PhorgeTokensToken extends PhorgeTokenDAO
  implements
    PhorgeDestructibleInterface,
    PhorgeSubscribableInterface,
    PhorgeFlaggableInterface,
    PhorgeConduitResultInterface {

  protected $name;
  protected $flavor;
  protected $status;
  protected $creatorPHID;
  protected $tokenImagePHID;
  protected $builtinKey;

  private $tokenImageFile = self::ATTACHABLE;

  const STATUS_ACTIVE = 'active';
  const STATUS_ARCHIVED = 'archived';

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID   => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text64',
        'flavor' => 'text128',
        'status' => 'text32',
        'tokenImagePHID' => 'phid?',
        'builtinKey' => 'text32?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_creator' => array(
          'columns' => array('creatorPHID', 'dateModified'),
        ),
        'key_builtin' => array(
          'columns' => array('builtinKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getTableName() {
    return 'token_token';
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeTokenTokenPHIDType::TYPECONST);
  }

  public static function initializeNewToken(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeTokensApplication'))
      ->executeOne();

    $token = id(new self())
      ->setCreatorPHID($actor->getPHID())
      ->setStatus(self::STATUS_ACTIVE)
      ->setTokenImagePHID('');
    return $token;
  }

  public function isArchived() {
    return ($this->getStatus() == self::STATUS_ARCHIVED);
  }

  public static function getStatusNameMap() {
    return array(
      self::STATUS_ACTIVE => pht('Active'),
      self::STATUS_ARCHIVED => pht('Archived'),
    );
  }

  public function getTokenImageURI() {
    return $this->getTokenImageFile()->getBestURI();
  }

  public function attachTokenImageFile(PhorgeFile $file) {
    $this->tokenImageFile = $file;
    return $this;
  }

  public function getTokenImageFile() {
    return $this->assertAttached($this->tokenImageFile);
  }

  public function getViewURI() {
    return '/tokens/view/'.$this->getID().'/';
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();

      $tokens = id(new PhorgeTokenGiven())
        ->loadAllWhere('tokenPHID = %s', $this->getPHID());
      foreach ($tokens as $token) {
        $token->delete();
      }
      if ($this->getTokenImagePHID()) {
        id(new PhorgeFile())
          ->loadOneWhere('filePHID = %s', $this->getTokenImagePHID())
          ->delete();
      }

      $this->delete();

    $this->saveTransaction();
  }

/* -(  PhorgeSubscribableInterface Implementation  )-------------------- */


  public function isAutomaticallySubscribed($phid) {
    return false;
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the token.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('flavor')
        ->setType('string')
        ->setDescription(pht('Token flavor.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('status')
        ->setType('string')
        ->setDescription(pht('Archived or active status.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
      'flavor' => $this->getFlavor(),
      'status' => $this->getStatus(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

}
