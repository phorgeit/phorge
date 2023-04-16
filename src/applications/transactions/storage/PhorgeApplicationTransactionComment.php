<?php

abstract class PhorgeApplicationTransactionComment
  extends PhorgeLiskDAO
  implements
    PhorgeMarkupInterface,
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  const MARKUP_FIELD_COMMENT  = 'markup:comment';

  protected $transactionPHID;
  protected $commentVersion;
  protected $authorPHID;
  protected $viewPolicy;
  protected $editPolicy;
  protected $content;
  protected $contentSource;
  protected $isDeleted = 0;

  private $oldComment = self::ATTACHABLE;

  abstract public function getApplicationTransactionObject();

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePHIDConstants::PHID_TYPE_XCMT);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'transactionPHID' => 'phid?',
        'commentVersion' => 'uint32',
        'content' => 'text',
        'contentSource' => 'text',
        'isDeleted' => 'bool',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_version' => array(
          'columns' => array('transactionPHID', 'commentVersion'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getApplicationName() {
    return $this->getApplicationTransactionObject()->getApplicationName();
  }

  public function getTableName() {
    $xaction = $this->getApplicationTransactionObject();
    return self::getTableNameFromTransaction($xaction);
  }

  public static function getTableNameFromTransaction(
    PhorgeApplicationTransaction $xaction) {
    return $xaction->getTableName().'_comment';
  }

  public function setContentSource(PhorgeContentSource $content_source) {
    $this->contentSource = $content_source->serialize();
    return $this;
  }

  public function setContentSourceFromRequest(AphrontRequest $request) {
    return $this->setContentSource(
      PhorgeContentSource::newFromRequest($request));
  }

  public function getContentSource() {
    return PhorgeContentSource::newFromSerialized($this->contentSource);
  }

  public function getIsRemoved() {
    return ($this->getIsDeleted() == 2);
  }

  public function setIsRemoved($removed) {
    if ($removed) {
      $this->setIsDeleted(2);
    } else {
      $this->setIsDeleted(0);
    }
    return $this;
  }

  public function attachOldComment(
    PhorgeApplicationTransactionComment $old_comment) {
    $this->oldComment = $old_comment;
    return $this;
  }

  public function getOldComment() {
    return $this->assertAttached($this->oldComment);
  }

  public function hasOldComment() {
    return ($this->oldComment !== self::ATTACHABLE);
  }

  public function getRawRemarkupURI() {
    return urisprintf(
      '/transactions/raw/%s/',
      $this->getTransactionPHID());
  }

  public function isEmptyComment() {
    $content = $this->getContent();

    // The comment is empty if there's no content, or if the content only has
    // whitespace.
    if (!strlen(trim($content))) {
      return true;
    }

    return false;
  }

/* -(  PhorgeMarkupInterface  )----------------------------------------- */


  public function getMarkupFieldKey($field) {
    return PhorgePHIDConstants::PHID_TYPE_XCMT.':'.$this->getPHID();
  }


  public function newMarkupEngine($field) {
    return PhorgeMarkupEngine::getEngine();
  }


  public function getMarkupText($field) {
    return $this->getContent();
  }


  public function didMarkupText($field, $output, PhutilMarkupEngine $engine) {
    require_celerity_resource('phorge-remarkup-css');
    return phutil_tag(
      'div',
      array(
        'class' => 'phorge-remarkup',
      ),
      $output);
  }


  public function shouldUseMarkupCache($field) {
    return (bool)$this->getPHID();
  }

/* -(  PhorgePolicyInterface Implementation  )-------------------------- */


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
    return ($viewer->getPHID() == $this->getAuthorPHID());
  }

  public function describeAutomaticCapability($capability) {
    return pht(
      'Comments are visible to users who can see the object which was '.
      'commented on. Comments can be edited by their authors.');
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->openTransaction();
      $this->delete();
    $this->saveTransaction();
  }

}
