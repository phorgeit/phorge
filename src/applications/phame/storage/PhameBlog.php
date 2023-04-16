<?php

final class PhameBlog extends PhameDAO
  implements
    PhorgePolicyInterface,
    PhorgeMarkupInterface,
    PhorgeSubscribableInterface,
    PhorgeFlaggableInterface,
    PhorgeProjectInterface,
    PhorgeDestructibleInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeConduitResultInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface {

  protected $name;
  protected $subtitle;
  protected $description;
  protected $domain;
  protected $domainFullURI;
  protected $parentSite;
  protected $parentDomain;
  protected $configData;
  protected $creatorPHID;
  protected $viewPolicy;
  protected $editPolicy;
  protected $interactPolicy;
  protected $status;
  protected $mailKey;
  protected $profileImagePHID;
  protected $headerImagePHID;

  private $profileImageFile = self::ATTACHABLE;
  private $headerImageFile = self::ATTACHABLE;

  const STATUS_ACTIVE = 'active';
  const STATUS_ARCHIVED = 'archived';

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID   => true,
      self::CONFIG_SERIALIZATION => array(
        'configData' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text64',
        'subtitle' => 'text64',
        'description' => 'text',
        'domain' => 'text128?',
        'domainFullURI' => 'text128?',
        'parentSite' => 'text128?',
        'parentDomain' => 'text128?',
        'status' => 'text32',
        'mailKey' => 'bytes20',
        'profileImagePHID' => 'phid?',
        'headerImagePHID' => 'phid?',

        'editPolicy' => 'policy',
        'viewPolicy' => 'policy',
        'interactPolicy' => 'policy',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
        'domain' => array(
          'columns' => array('domain'),
          'unique' => true,
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
      PhorgePhameBlogPHIDType::TYPECONST);
  }

  public static function initializeNewBlog(PhorgeUser $actor) {
    $blog = id(new PhameBlog())
      ->setCreatorPHID($actor->getPHID())
      ->setStatus(self::STATUS_ACTIVE)
      ->setViewPolicy(PhorgePolicies::getMostOpenPolicy())
      ->setEditPolicy(PhorgePolicies::POLICY_USER)
      ->setInteractPolicy(PhorgePolicies::POLICY_USER);

    return $blog;
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

  /**
   * Makes sure a given custom blog uri is properly configured in DNS
   * to point at this Phorge instance. If there is an error in
   * the configuration, return a string describing the error and how
   * to fix it. If there is no error, return an empty string.
   *
   * @return string
   */
  public function validateCustomDomain($domain_full_uri) {
    $example_domain = 'http://blog.example.com/';
    $label = pht('Invalid');

    // note this "uri" should be pretty busted given the desired input
    // so just use it to test if there's a protocol specified
    $uri = new PhutilURI($domain_full_uri);
    $domain = $uri->getDomain();
    $protocol = $uri->getProtocol();
    $path = $uri->getPath();
    $supported_protocols = array('http', 'https');

    if (!in_array($protocol, $supported_protocols)) {
      return pht(
          'The custom domain should include a valid protocol in the URI '.
          '(for example, "%s"). Valid protocols are "http" or "https".',
          $example_domain);
    }

    if (strlen($path) && $path != '/') {
      return pht(
          'The custom domain should not specify a path (hosting a Phame '.
          'blog at a path is currently not supported). Instead, just provide '.
          'the bare domain name (for example, "%s").',
          $example_domain);
    }

    if (strpos($domain, '.') === false) {
      return pht(
          'The custom domain should contain at least one dot (.) because '.
          'some browsers fail to set cookies on domains without a dot. '.
          'Instead, use a normal looking domain name like "%s".',
          $example_domain);
    }

    if (!PhorgeEnv::getEnvConfig('policy.allow-public')) {
      $href = PhorgeEnv::getProductionURI(
        '/config/edit/policy.allow-public/');
      return pht(
        'For custom domains to work, this this server must be '.
        'configured to allow the public access policy. Configure this '.
        'setting %s, or ask an administrator to configure this setting. '.
        'The domain can be specified later once this setting has been '.
        'changed.',
        phutil_tag(
          'a',
          array('href' => $href),
          pht('here')));
    }

    return null;
  }

  public function getLiveURI() {
    if (strlen($this->getDomain())) {
      return $this->getExternalLiveURI();
    } else {
      return $this->getInternalLiveURI();
    }
  }

  public function getExternalLiveURI() {
    $uri = new PhutilURI($this->getDomainFullURI());
    PhorgeEnv::requireValidRemoteURIForLink($uri);
    return (string)$uri;
  }

  public function getExternalParentURI() {
    $uri = $this->getParentDomain();
    PhorgeEnv::requireValidRemoteURIForLink($uri);
    return (string)$uri;
  }

  public function getInternalLiveURI() {
    return '/phame/live/'.$this->getID().'/';
  }

  public function getViewURI() {
    return '/phame/blog/view/'.$this->getID().'/';
  }

  public function getManageURI() {
    return '/phame/blog/manage/'.$this->getID().'/';
  }

  public function getProfileImageURI() {
    return $this->getProfileImageFile()->getBestURI();
  }

  public function attachProfileImageFile(PhorgeFile $file) {
    $this->profileImageFile = $file;
    return $this;
  }

  public function getProfileImageFile() {
    return $this->assertAttached($this->profileImageFile);
  }

  public function getHeaderImageURI() {
    return $this->getHeaderImageFile()->getBestURI();
  }

  public function attachHeaderImageFile(PhorgeFile $file) {
    $this->headerImageFile = $file;
    return $this;
  }

  public function getHeaderImageFile() {
    return $this->assertAttached($this->headerImageFile);
  }


/* -(  PhorgePolicyInterface Implementation  )-------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_INTERACT,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }


  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return $this->getViewPolicy();
      case PhorgePolicyCapability::CAN_INTERACT:
        return $this->getInteractPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $user) {
    $can_edit = PhorgePolicyCapability::CAN_EDIT;

    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        // Users who can edit or post to a blog can always view it.
        if (PhorgePolicyFilter::hasCapability($user, $this, $can_edit)) {
          return true;
        }
        break;
    }

    return false;
  }


  public function describeAutomaticCapability($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return pht(
          'Users who can edit a blog can always view it.');
    }

    return null;
  }


/* -(  PhorgeMarkupInterface Implementation  )-------------------------- */


  public function getMarkupFieldKey($field) {
    $content = $this->getMarkupText($field);
    return PhorgeMarkupEngine::digestRemarkupContent($this, $content);
  }


  public function newMarkupEngine($field) {
    return PhorgeMarkupEngine::newPhameMarkupEngine();
  }


  public function getMarkupText($field) {
    return $this->getDescription();
  }


  public function didMarkupText(
    $field,
    $output,
    PhutilMarkupEngine $engine) {
    return $output;
  }

  public function shouldUseMarkupCache($field) {
    return (bool)$this->getPHID();
  }

/* -(  PhorgeDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();

      $posts = id(new PhamePostQuery())
        ->setViewer($engine->getViewer())
        ->withBlogPHIDs(array($this->getPHID()))
        ->execute();
      foreach ($posts as $post) {
        $engine->destroyObject($post);
      }
      $this->delete();

    $this->saveTransaction();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhameBlogEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhameBlogTransaction();
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
        ->setDescription(pht('The name of the blog.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('description')
        ->setType('string')
        ->setDescription(pht('Blog description.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('status')
        ->setType('string')
        ->setDescription(pht('Archived or active status.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
      'description' => $this->getDescription(),
      'status' => $this->getStatus(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }


/* -(  PhorgeFulltextInterface  )--------------------------------------- */

  public function newFulltextEngine() {
    return new PhameBlogFulltextEngine();
  }


/* -(  PhorgeFerretInterface  )----------------------------------------- */


  public function newFerretEngine() {
    return new PhameBlogFerretEngine();
  }

}
