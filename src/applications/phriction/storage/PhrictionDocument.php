<?php

final class PhrictionDocument extends PhrictionDAO
  implements
    PhorgePolicyInterface,
    PhorgeSubscribableInterface,
    PhorgeFlaggableInterface,
    PhorgeTokenReceiverInterface,
    PhorgeDestructibleInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface,
    PhorgeProjectInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeConduitResultInterface,
    PhorgePolicyCodexInterface,
    PhorgeSpacesInterface {

  protected $slug;
  protected $depth;
  protected $contentPHID;
  protected $status;
  protected $viewPolicy;
  protected $editPolicy;
  protected $spacePHID;
  protected $editedEpoch;
  protected $maxVersion;

  private $contentObject = self::ATTACHABLE;
  private $ancestors = array();

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID   => true,
      self::CONFIG_TIMESTAMPS => false,
      self::CONFIG_COLUMN_SCHEMA => array(
        'slug' => 'sort128',
        'depth' => 'uint32',
        'status' => 'text32',
        'editedEpoch' => 'epoch',
        'maxVersion' => 'uint32',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'slug' => array(
          'columns' => array('slug'),
          'unique' => true,
        ),
        'depth' => array(
          'columns' => array('depth', 'slug'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhrictionDocumentPHIDType::TYPECONST;
  }

  public static function initializeNewDocument(PhorgeUser $actor, $slug) {
    $document = id(new self())
      ->setSlug($slug);

    $content = id(new PhrictionContent())
      ->setSlug($slug);

    $default_title = PhorgeSlug::getDefaultTitle($slug);
    $content->setTitle($default_title);
    $document->attachContent($content);

    $parent_doc = null;
    $ancestral_slugs = PhorgeSlug::getAncestry($slug);
    if ($ancestral_slugs) {
      $parent = end($ancestral_slugs);
      $parent_doc = id(new PhrictionDocumentQuery())
        ->setViewer($actor)
        ->withSlugs(array($parent))
        ->executeOne();
    }

    if ($parent_doc) {
      $space_phid = PhorgeSpacesNamespaceQuery::getObjectSpacePHID(
        $parent_doc);

      $document
        ->setViewPolicy($parent_doc->getViewPolicy())
        ->setEditPolicy($parent_doc->getEditPolicy())
        ->setSpacePHID($space_phid);
    } else {
      $default_view_policy = PhorgePolicies::getMostOpenPolicy();
      $document
        ->setViewPolicy($default_view_policy)
        ->setEditPolicy(PhorgePolicies::POLICY_USER)
        ->setSpacePHID($actor->getDefaultSpacePHID());
    }

    $document->setEditedEpoch(PhorgeTime::getNow());
    $document->setMaxVersion(0);

    return $document;
  }

  public static function getSlugURI($slug, $type = 'document') {
    static $types = array(
      'document'  => '/w/',
      'history'   => '/phriction/history/',
    );

    if (empty($types[$type])) {
      throw new Exception(pht("Unknown URI type '%s'!", $type));
    }

    $prefix = $types[$type];

    if ($slug == '/') {
      return $prefix;
    } else {
      // NOTE: The effect here is to escape non-latin characters, since modern
      // browsers deal with escaped UTF8 characters in a reasonable way (showing
      // the user a readable URI) but older programs may not.
      $slug = phutil_escape_uri($slug);
      return $prefix.$slug;
    }
  }

  public function setSlug($slug) {
    $this->slug   = PhorgeSlug::normalize($slug);
    $this->depth  = PhorgeSlug::getDepth($slug);
    return $this;
  }

  public function attachContent(PhrictionContent $content) {
    $this->contentObject = $content;
    return $this;
  }

  public function getContent() {
    return $this->assertAttached($this->contentObject);
  }

  public function getAncestors() {
    return $this->ancestors;
  }

  public function getAncestor($slug) {
    return $this->assertAttachedKey($this->ancestors, $slug);
  }

  public function attachAncestor($slug, $ancestor) {
    $this->ancestors[$slug] = $ancestor;
    return $this;
  }

  public function getURI() {
    return self::getSlugURI($this->getSlug());
  }

/* -(  Status  )------------------------------------------------------------- */


  public function getStatusObject() {
    return PhrictionDocumentStatus::newStatusObject($this->getStatus());
  }

  public function getStatusIcon() {
    return $this->getStatusObject()->getIcon();
  }

  public function getStatusColor() {
    return $this->getStatusObject()->getColor();
  }

  public function getStatusDisplayName() {
    return $this->getStatusObject()->getDisplayName();
  }

  public function isActive() {
    return $this->getStatusObject()->isActive();
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

  public function hasAutomaticCapability($capability, PhorgeUser $user) {
    return false;
  }


/* -(  PhorgeSpacesInterface  )----------------------------------------- */


  public function getSpacePHID() {
    return $this->spacePHID;
  }



/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return false;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhrictionTransactionEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhrictionTransaction();
  }


/* -(  PhorgeTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return PhorgeSubscribersQuery::loadSubscribersForPHID($this->phid);
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();

      $contents = id(new PhrictionContentQuery())
        ->setViewer($engine->getViewer())
        ->withDocumentPHIDs(array($this->getPHID()))
        ->execute();
      foreach ($contents as $content) {
        $engine->destroyObject($content);
      }

      $this->delete();

    $this->saveTransaction();
  }


/* -(  PhorgeFulltextInterface  )--------------------------------------- */


  public function newFulltextEngine() {
    return new PhrictionDocumentFulltextEngine();
  }


/* -(  PhorgeFerretInterface  )----------------------------------------- */


  public function newFerretEngine() {
    return new PhrictionDocumentFerretEngine();
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('path')
        ->setType('string')
        ->setDescription(pht('The path to the document.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('status')
        ->setType('map<string, wild>')
        ->setDescription(pht('Status information about the document.')),
    );
  }

  public function getFieldValuesForConduit() {
    $status = array(
      'value' => $this->getStatus(),
      'name' => $this->getStatusDisplayName(),
    );

    return array(
      'path' => $this->getSlug(),
      'status' => $status,
    );
  }

  public function getConduitSearchAttachments() {
    return array(
      id(new PhrictionContentSearchEngineAttachment())
        ->setAttachmentKey('content'),
    );
  }

/* -(  PhorgePolicyCodexInterface  )------------------------------------ */


  public function newPolicyCodex() {
    return new PhrictionDocumentPolicyCodex();
  }


}
