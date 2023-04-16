<?php

final class PhorgeFileAttachment
  extends PhorgeFileDAO
  implements
    PhorgePolicyInterface,
    PhorgeExtendedPolicyInterface {

  protected $objectPHID;
  protected $filePHID;
  protected $attacherPHID;
  protected $attachmentMode;

  private $object = self::ATTACHABLE;
  private $file = self::ATTACHABLE;

  const MODE_ATTACH = 'attach';
  const MODE_REFERENCE = 'reference';
  const MODE_DETACH = 'detach';

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'objectPHID' => 'phid',
        'filePHID' => 'phid',
        'attacherPHID' => 'phid?',
        'attachmentMode' => 'text32',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_object' => array(
          'columns' => array('objectPHID', 'filePHID'),
          'unique' => true,
        ),
        'key_file' => array(
          'columns' => array('filePHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public static function getModeList() {
    return array(
      self::MODE_ATTACH,
      self::MODE_REFERENCE,
      self::MODE_DETACH,
    );
  }

  public static function getModeNameMap() {
    return array(
      self::MODE_ATTACH => pht('Attached'),
      self::MODE_REFERENCE => pht('Referenced'),
    );
  }

  public function isPolicyAttachment() {
    switch ($this->getAttachmentMode()) {
      case self::MODE_ATTACH:
        return true;
      default:
        return false;
    }
  }

  public function attachObject($object) {
    $this->object = $object;
    return $this;
  }

  public function getObject() {
    return $this->assertAttached($this->object);
  }

  public function attachFile(PhorgeFile $file = null) {
    $this->file = $file;
    return $this;
  }

  public function getFile() {
    return $this->assertAttached($this->file);
  }

  public function canDetach() {
    switch ($this->getAttachmentMode()) {
      case self::MODE_ATTACH:
        return true;
    }

    return false;
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::getMostOpenPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeExtendedPolicyInterface  )--------------------------------- */


  public function getExtendedPolicy($capability, PhorgeUser $viewer) {
    return array(
      array($this->getObject(), $capability),
    );
  }

}
