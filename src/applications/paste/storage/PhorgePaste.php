<?php

final class PhorgePaste extends PhorgePasteDAO
  implements
    PhorgeSubscribableInterface,
    PhorgeTokenReceiverInterface,
    PhorgeFlaggableInterface,
    PhorgeMentionableInterface,
    PhorgePolicyInterface,
    PhorgeProjectInterface,
    PhorgeDestructibleInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeSpacesInterface,
    PhorgeConduitResultInterface,
    PhorgeFerretInterface,
    PhorgeFulltextInterface {

  protected $title;
  protected $authorPHID;
  protected $filePHID;
  protected $language;
  protected $parentPHID;
  protected $viewPolicy;
  protected $editPolicy;
  protected $mailKey;
  protected $status;
  protected $spacePHID;

  const STATUS_ACTIVE = 'active';
  const STATUS_ARCHIVED = 'archived';

  private $content = self::ATTACHABLE;
  private $rawContent = self::ATTACHABLE;
  private $snippet = self::ATTACHABLE;

  public static function initializeNewPaste(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgePasteApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(PasteDefaultViewCapability::CAPABILITY);
    $edit_policy = $app->getPolicy(PasteDefaultEditCapability::CAPABILITY);

    return id(new PhorgePaste())
      ->setTitle('')
      ->setStatus(self::STATUS_ACTIVE)
      ->setAuthorPHID($actor->getPHID())
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy)
      ->setSpacePHID($actor->getDefaultSpacePHID())
      ->attachRawContent(null);
  }

  public static function getStatusNameMap() {
    return array(
      self::STATUS_ACTIVE => pht('Active'),
      self::STATUS_ARCHIVED => pht('Archived'),
    );
  }

  public function getURI() {
    return '/'.$this->getMonogram();
  }

  public function getMonogram() {
    return 'P'.$this->getID();
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'status' => 'text32',
        'title' => 'text255',
        'language' => 'text64?',
        'mailKey' => 'bytes20',
        'parentPHID' => 'phid?',

        // T6203/NULLABILITY
        // Pastes should always have a view policy.
        'viewPolicy' => 'policy?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'parentPHID' => array(
          'columns' => array('parentPHID'),
        ),
        'authorPHID' => array(
          'columns' => array('authorPHID'),
        ),
        'key_dateCreated' => array(
          'columns' => array('dateCreated'),
        ),
        'key_language' => array(
          'columns' => array('language'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePastePastePHIDType::TYPECONST);
  }

  public function isArchived() {
    return ($this->getStatus() == self::STATUS_ARCHIVED);
  }

  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }

  public function getFullName() {
    $title = $this->getTitle();
    if (!$title) {
      $title = pht('(An Untitled Masterwork)');
    }
    return 'P'.$this->getID().' '.$title;
  }

  public function getContent() {
    return $this->assertAttached($this->content);
  }

  public function attachContent($content) {
    $this->content = $content;
    return $this;
  }

  public function getRawContent() {
    return $this->assertAttached($this->rawContent);
  }

  public function attachRawContent($raw_content) {
    $this->rawContent = $raw_content;
    return $this;
  }

  public function getSnippet() {
    return $this->assertAttached($this->snippet);
  }

  public function attachSnippet(PhorgePasteSnippet $snippet) {
    $this->snippet = $snippet;
    return $this;
  }

/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($this->authorPHID == $phid);
  }


/* -(  PhorgeTokenReceiverInterface  )---------------------------------- */

  public function getUsersToNotifyOfTokenGiven() {
    return array(
      $this->getAuthorPHID(),
    );
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    if ($capability == PhorgePolicyCapability::CAN_VIEW) {
      return $this->viewPolicy;
    } else if ($capability == PhorgePolicyCapability::CAN_EDIT) {
      return $this->editPolicy;
    }
    return PhorgePolicies::POLICY_NOONE;
  }

  public function hasAutomaticCapability($capability, PhorgeUser $user) {
    return ($user->getPHID() == $this->getAuthorPHID());
  }

  public function describeAutomaticCapability($capability) {
    return pht('The author of a paste can always view and edit it.');
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    if ($this->filePHID) {
      $file = id(new PhorgeFileQuery())
        ->setViewer($engine->getViewer())
        ->withPHIDs(array($this->filePHID))
        ->executeOne();
      if ($file) {
        $engine->destroyObject($file);
      }
    }

    $this->delete();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgePasteEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgePasteTransaction();
  }


/* -(  PhorgeSpacesInterface  )----------------------------------------- */


  public function getSpacePHID() {
    return $this->spacePHID;
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('title')
        ->setType('string')
        ->setDescription(pht('The title of the paste.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('uri')
        ->setType('uri')
        ->setDescription(pht('View URI for the paste.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('authorPHID')
        ->setType('phid')
        ->setDescription(pht('User PHID of the author.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('language')
        ->setType('string?')
        ->setDescription(pht('Language to use for syntax highlighting.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('status')
        ->setType('string')
        ->setDescription(pht('Active or archived status of the paste.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'title' => $this->getTitle(),
      'uri' => PhorgeEnv::getURI($this->getURI()),
      'authorPHID' => $this->getAuthorPHID(),
      'language' => nonempty($this->getLanguage(), null),
      'status' => $this->getStatus(),
    );
  }

  public function getConduitSearchAttachments() {
    return array(
      id(new PhorgePasteContentSearchEngineAttachment())
        ->setAttachmentKey('content'),
    );
  }


/* -(  PhorgeFerretInterface  )----------------------------------------- */


  public function newFerretEngine() {
    return new PhorgePasteFerretEngine();
  }


/* -(  PhorgeFulltextInterface  )--------------------------------------- */

  public function newFulltextEngine() {
    return new PhorgePasteFulltextEngine();
  }

}
