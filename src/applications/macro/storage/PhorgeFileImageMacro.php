<?php

final class PhorgeFileImageMacro extends PhorgeFileDAO
  implements
    PhorgeSubscribableInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeFlaggableInterface,
    PhorgeTokenReceiverInterface,
    PhorgePolicyInterface {

  protected $authorPHID;
  protected $filePHID;
  protected $name;
  protected $isDisabled = 0;
  protected $audioPHID;
  protected $audioBehavior = self::AUDIO_BEHAVIOR_NONE;
  protected $mailKey;

  private $file = self::ATTACHABLE;
  private $audio = self::ATTACHABLE;

  const AUDIO_BEHAVIOR_NONE   = 'audio:none';
  const AUDIO_BEHAVIOR_ONCE   = 'audio:once';
  const AUDIO_BEHAVIOR_LOOP   = 'audio:loop';

  public function attachFile(PhorgeFile $file) {
    $this->file = $file;
    return $this;
  }

  public function getFile() {
    return $this->assertAttached($this->file);
  }

  public function attachAudio(PhorgeFile $audio = null) {
    $this->audio = $audio;
    return $this;
  }

  public function getAudio() {
    return $this->assertAttached($this->audio);
  }

  public static function initializeNewFileImageMacro(PhorgeUser $actor) {
    $macro = id(new self())
      ->setAuthorPHID($actor->getPHID());
    return $macro;
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID  => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text128',
        'authorPHID' => 'phid?',
        'isDisabled' => 'bool',
        'audioPHID' => 'phid?',
        'audioBehavior' => 'text64',
        'mailKey' => 'bytes20',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'name' => array(
          'columns' => array('name'),
          'unique' => true,
        ),
        'key_disabled' => array(
          'columns' => array('isDisabled'),
        ),
        'key_dateCreated' => array(
          'columns' => array('dateCreated'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeMacroMacroPHIDType::TYPECONST);
  }


  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }

  public function getViewURI() {
    return '/macro/view/'.$this->getID().'/';
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeMacroEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeMacroTransaction();
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return false;
  }


/* -(  PhorgeTokenRecevierInterface  )---------------------------------- */


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
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::getMostOpenPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        $app = PhorgeApplication::getByClass(
          'PhorgeMacroApplication');
        return $app->getPolicy(PhorgeMacroManageCapability::CAPABILITY);
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
