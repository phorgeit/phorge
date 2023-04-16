<?php

final class PhorgeAuthMessage
  extends PhorgeAuthDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeDestructibleInterface {

  protected $messageKey;
  protected $messageText;

  private $messageType = self::ATTACHABLE;

  public static function initializeNewMessage(
    PhorgeAuthMessageType $type) {

    return id(new self())
      ->setMessageKey($type->getMessageTypeKey())
      ->attachMessageType($type);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'messageKey' => 'text64',
        'messageText' => 'text',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_type' => array(
          'columns' => array('messageKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeAuthMessagePHIDType::TYPECONST;
  }

  public function getObjectName() {
    return pht('Auth Message %d', $this->getID());
  }

  public function getURI() {
    return urisprintf('/auth/message/%s/', $this->getID());
  }

  public function attachMessageType(PhorgeAuthMessageType $type) {
    $this->messageType = $type;
    return $this;
  }

  public function getMessageType() {
    return $this->assertAttached($this->messageType);
  }

  public function getMessageTypeDisplayName() {
    return $this->getMessageType()->getDisplayName();
  }

  public static function loadMessage(
    PhorgeUser $viewer,
    $message_key) {
    return id(new PhorgeAuthMessageQuery())
      ->setViewer($viewer)
      ->withMessageKeys(array($message_key))
      ->executeOne();
  }

  public static function loadMessageText(
    PhorgeUser $viewer,
    $message_key) {

    $message = self::loadMessage($viewer, $message_key);
    if ($message) {
      $message_text = $message->getMessageText();
      if (strlen($message_text)) {
        return $message_text;
      }
    }

    $message_type = PhorgeAuthMessageType::newFromKey($message_key);

    return $message_type->getDefaultMessageText();
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
      default:
        return false;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        // Even if an install doesn't allow public users, you can still view
        // auth messages: otherwise, we can't do things like show you
        // guidance on the login screen.
        return true;
      default:
        return false;
    }
  }

/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeAuthMessageEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeAuthMessageTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }

}
