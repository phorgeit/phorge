<?php

final class PholioMock extends PholioDAO
  implements
    PhorgePolicyInterface,
    PhorgeSubscribableInterface,
    PhorgeTokenReceiverInterface,
    PhorgeFlaggableInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeTimelineInterface,
    PhorgeProjectInterface,
    PhorgeDestructibleInterface,
    PhorgeSpacesInterface,
    PhorgeMentionableInterface,
    PhorgeFulltextInterface,
    PhorgeFerretInterface {

  const STATUS_OPEN = 'open';
  const STATUS_CLOSED = 'closed';

  protected $authorPHID;
  protected $viewPolicy;
  protected $editPolicy;

  protected $name;
  protected $description;
  protected $coverPHID;
  protected $status;
  protected $spacePHID;

  private $images = self::ATTACHABLE;
  private $coverFile = self::ATTACHABLE;
  private $tokenCount = self::ATTACHABLE;

  public static function initializeNewMock(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgePholioApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(PholioDefaultViewCapability::CAPABILITY);
    $edit_policy = $app->getPolicy(PholioDefaultEditCapability::CAPABILITY);

    return id(new PholioMock())
      ->setAuthorPHID($actor->getPHID())
      ->attachImages(array())
      ->setStatus(self::STATUS_OPEN)
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy)
      ->setSpacePHID($actor->getDefaultSpacePHID());
  }

  public function getMonogram() {
    return 'M'.$this->getID();
  }

  public function getURI() {
    return '/'.$this->getMonogram();
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text128',
        'description' => 'text',
        'status' => 'text12',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'authorPHID' => array(
          'columns' => array('authorPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PholioMockPHIDType::TYPECONST;
  }

  public function attachImages(array $images) {
    assert_instances_of($images, 'PholioImage');
    $images = mpull($images, null, 'getPHID');
    $images = msort($images, 'getSequence');
    $this->images = $images;
    return $this;
  }

  public function getImages() {
    return $this->assertAttached($this->images);
  }

  public function getActiveImages() {
    $images = $this->getImages();

    foreach ($images as $phid => $image) {
      if ($image->getIsObsolete()) {
        unset($images[$phid]);
      }
    }

    return $images;
  }

  public function attachCoverFile(PhorgeFile $file) {
    $this->coverFile = $file;
    return $this;
  }

  public function getCoverFile() {
    $this->assertAttached($this->coverFile);
    return $this->coverFile;
  }

  public function getTokenCount() {
    $this->assertAttached($this->tokenCount);
    return $this->tokenCount;
  }

  public function attachTokenCount($count) {
    $this->tokenCount = $count;
    return $this;
  }

  public function getImageHistorySet($image_id) {
    $images = $this->getImages();
    $images = mpull($images, null, 'getID');
    $selected_image = $images[$image_id];

    $replace_map = mpull($images, null, 'getReplacesImagePHID');
    $phid_map = mpull($images, null, 'getPHID');

    // find the earliest image
    $image = $selected_image;
    while (isset($phid_map[$image->getReplacesImagePHID()])) {
      $image = $phid_map[$image->getReplacesImagePHID()];
    }

    // now build history moving forward
    $history = array($image->getID() => $image);
    while (isset($replace_map[$image->getPHID()])) {
      $image = $replace_map[$image->getPHID()];
      $history[$image->getID()] = $image;
    }

    return $history;
  }

  public function getStatuses() {
    $options = array();
    $options[self::STATUS_OPEN] = pht('Open');
    $options[self::STATUS_CLOSED] = pht('Closed');
    return $options;
  }

  public function isClosed() {
    return ($this->getStatus() == 'closed');
  }


/* -(  PhorgeSubscribableInterface Implementation  )-------------------- */


  public function isAutomaticallySubscribed($phid) {
    return ($this->authorPHID == $phid);
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
    return pht("A mock's owner can always view and edit it.");
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PholioMockEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PholioTransaction();
  }


/* -(  PhorgeTokenReceiverInterface  )---------------------------------- */


  public function getUsersToNotifyOfTokenGiven() {
    return array(
      $this->getAuthorPHID(),
    );
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
      $images = id(new PholioImageQuery())
        ->setViewer($engine->getViewer())
        ->withMockPHIDs(array($this->getPHID()))
        ->execute();
      foreach ($images as $image) {
        $image->delete();
      }

      $this->delete();
    $this->saveTransaction();
  }


/* -(  PhorgeSpacesInterface  )----------------------------------------- */


  public function getSpacePHID() {
    return $this->spacePHID;
  }


/* -(  PhorgeFulltextInterface  )--------------------------------------- */


  public function newFulltextEngine() {
    return new PholioMockFulltextEngine();
  }


/* -(  PhorgeFerretInterface  )----------------------------------------- */


  public function newFerretEngine() {
    return new PholioMockFerretEngine();
  }


/* -(  PhorgeTimelineInterace  )---------------------------------------- */


  public function newTimelineEngine() {
    return new PholioMockTimelineEngine();
  }


}
