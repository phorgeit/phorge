<?php

final class PhorgeBadgesAward extends PhorgeBadgesDAO
  implements
    PhorgeDestructibleInterface,
    PhorgePolicyInterface {

  protected $badgePHID;
  protected $recipientPHID;
  protected $awarderPHID;

  private $badge = self::ATTACHABLE;

  public static function initializeNewBadgesAward(
    PhorgeUser $actor,
    PhorgeBadgesBadge $badge,
    $recipient_phid) {
    return id(new self())
      ->setRecipientPHID($recipient_phid)
      ->setBadgePHID($badge->getPHID())
      ->setAwarderPHID($actor->getPHID())
      ->attachBadge($badge);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_KEY_SCHEMA => array(
        'key_badge' => array(
          'columns' => array('badgePHID', 'recipientPHID'),
          'unique' => true,
        ),
        'key_recipient' => array(
          'columns' => array('recipientPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function attachBadge(PhorgeBadgesBadge $badge) {
    $this->badge = $badge;
    return $this;
  }

  public function getBadge() {
    return $this->assertAttached($this->badge);
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {

    $this->openTransaction();
      $this->delete();
    $this->saveTransaction();
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    return $this->getBadge()->getPolicy($capability);
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
