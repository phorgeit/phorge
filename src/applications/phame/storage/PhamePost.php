<?php

final class PhamePost extends PhameDAO
  implements
    PhorgePolicyInterface,
    PhorgeMarkupInterface,
    PhorgeFlaggableInterface,
    PhorgeProjectInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeSubscribableInterface,
    PhorgeDestructibleInterface,
    PhorgeTokenReceiverInterface,
    PhorgeConduitResultInterface,
    PhorgeEditEngineLockableInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface {

  const MARKUP_FIELD_BODY    = 'markup:body';

  protected $bloggerPHID;
  protected $title;
  protected $subtitle;
  protected $phameTitle;
  protected $body;
  protected $visibility;
  protected $configData;
  protected $datePublished;
  protected $blogPHID;
  protected $mailKey;
  protected $headerImagePHID;
  protected $interactPolicy;

  private $blog = self::ATTACHABLE;
  private $headerImageFile = self::ATTACHABLE;

  public static function initializePost(
    PhorgeUser $blogger,
    PhameBlog $blog) {

    $post = id(new PhamePost())
      ->setBloggerPHID($blogger->getPHID())
      ->setBlogPHID($blog->getPHID())
      ->attachBlog($blog)
      ->setDatePublished(PhorgeTime::getNow())
      ->setVisibility(PhameConstants::VISIBILITY_PUBLISHED)
      ->setInteractPolicy(
        id(new PhameInheritBlogPolicyRule())
          ->getObjectPolicyFullKey());

    return $post;
  }

  public function attachBlog(PhameBlog $blog) {
    $this->blog = $blog;
    return $this;
  }

  public function getBlog() {
    return $this->assertAttached($this->blog);
  }

  public function getMonogram() {
    return 'J'.$this->getID();
  }

  public function getLiveURI() {
    $blog = $this->getBlog();
    $is_draft = $this->isDraft();
    $is_archived = $this->isArchived();
    if (strlen($blog->getDomain()) && !$is_draft && !$is_archived) {
      return $this->getExternalLiveURI();
    } else {
      return $this->getInternalLiveURI();
    }
  }

  public function getExternalLiveURI() {
    $id = $this->getID();
    $slug = $this->getSlug();
    $path = "/post/{$id}/{$slug}/";

    $domain = $this->getBlog()->getDomain();

    return (string)id(new PhutilURI('http://'.$domain))
      ->setPath($path);
  }

  public function getInternalLiveURI() {
    $id = $this->getID();
    $slug = $this->getSlug();
    $blog_id = $this->getBlog()->getID();
    return "/phame/live/{$blog_id}/post/{$id}/{$slug}/";
  }

  public function getViewURI() {
    $id = $this->getID();
    $slug = $this->getSlug();
    return "/phame/post/view/{$id}/{$slug}/";
  }

  public function getBestURI($is_live, $is_external) {
    if ($is_live) {
      if ($is_external) {
        return $this->getExternalLiveURI();
      } else {
        return $this->getInternalLiveURI();
      }
    } else {
      return $this->getViewURI();
    }
  }

  public function getEditURI() {
    return '/phame/post/edit/'.$this->getID().'/';
  }

  public function isDraft() {
    return ($this->getVisibility() == PhameConstants::VISIBILITY_DRAFT);
  }

  public function isArchived() {
    return ($this->getVisibility() == PhameConstants::VISIBILITY_ARCHIVED);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID   => true,
      self::CONFIG_SERIALIZATION => array(
        'configData' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'title' => 'text255',
        'subtitle' => 'text64',
        'phameTitle' => 'sort64?',
        'visibility' => 'uint32',
        'mailKey' => 'bytes20',
        'headerImagePHID' => 'phid?',

        // T6203/NULLABILITY
        // These seem like they should always be non-null?
        'blogPHID' => 'phid?',
        'body' => 'text?',
        'configData' => 'text?',

        // T6203/NULLABILITY
        // This one probably should be nullable?
        'datePublished' => 'epoch',

        'interactPolicy' => 'policy',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_phid' => null,
        'phid' => array(
          'columns' => array('phid'),
          'unique' => true,
        ),
        'bloggerPosts' => array(
          'columns' => array(
            'bloggerPHID',
            'visibility',
            'datePublished',
            'id',
          ),
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
      PhorgePhamePostPHIDType::TYPECONST);
  }

  public function getSlug() {
    return PhorgeSlug::normalizeProjectSlug($this->getTitle());
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
      PhorgePolicyCapability::CAN_EDIT,
      PhorgePolicyCapability::CAN_INTERACT,
    );
  }

  public function getPolicy($capability) {
    // Draft and archived posts are visible only to the author and other
    // users who can edit the blog. Published posts are visible to whoever
    // the blog is visible to.

    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        if (!$this->isDraft() && !$this->isArchived() && $this->getBlog()) {
          return $this->getBlog()->getViewPolicy();
        } else if ($this->getBlog()) {
          return $this->getBlog()->getEditPolicy();
        } else {
          return PhorgePolicies::POLICY_NOONE;
        }
        break;
      case PhorgePolicyCapability::CAN_EDIT:
        if ($this->getBlog()) {
          return $this->getBlog()->getEditPolicy();
        } else {
          return PhorgePolicies::POLICY_NOONE;
        }
      case PhorgePolicyCapability::CAN_INTERACT:
        return $this->getInteractPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $user) {
    // A blog post's author can always view it.

    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
      case PhorgePolicyCapability::CAN_EDIT:
        return ($user->getPHID() == $this->getBloggerPHID());
      case PhorgePolicyCapability::CAN_INTERACT:
        return false;
    }
  }

  public function describeAutomaticCapability($capability) {
    return pht('The author of a blog post can always view and edit it.');
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
    switch ($field) {
      case self::MARKUP_FIELD_BODY:
        return $this->getBody();
    }
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


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhamePostEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhamePostTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
      $this->delete();
    $this->saveTransaction();
  }


/* -(  PhorgeTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array(
      $this->getBloggerPHID(),
    );
  }


/* -(  PhorgeSubscribableInterface Implementation  )-------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($this->bloggerPHID == $phid);
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('title')
        ->setType('string')
        ->setDescription(pht('Title of the post.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('slug')
        ->setType('string')
        ->setDescription(pht('Slug for the post.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('blogPHID')
        ->setType('phid')
        ->setDescription(pht('PHID of the blog that the post belongs to.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('authorPHID')
        ->setType('phid')
        ->setDescription(pht('PHID of the author of the post.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('body')
        ->setType('string')
        ->setDescription(pht('Body of the post.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('datePublished')
        ->setType('epoch?')
        ->setDescription(pht('Publish date, if the post has been published.')),

    );
  }

  public function getFieldValuesForConduit() {
    if ($this->isDraft()) {
      $date_published = null;
    } else if ($this->isArchived()) {
      $date_published = null;
    } else {
      $date_published = (int)$this->getDatePublished();
    }

    return array(
      'title' => $this->getTitle(),
      'slug' => $this->getSlug(),
      'blogPHID' => $this->getBlogPHID(),
      'authorPHID' => $this->getBloggerPHID(),
      'body' => $this->getBody(),
      'datePublished' => $date_published,
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }


/* -(  PhorgeFulltextInterface  )--------------------------------------- */

  public function newFulltextEngine() {
    return new PhamePostFulltextEngine();
  }


/* -(  PhorgeFerretInterface  )----------------------------------------- */


  public function newFerretEngine() {
    return new PhamePostFerretEngine();
  }


/* -(  PhorgeEditEngineLockableInterface  )----------------------------- */

  public function newEditEngineLock() {
    return new PhamePostEditEngineLock();
  }

}
