<?php

final class PhorgeCalendarEventInvitee extends PhorgeCalendarDAO
  implements PhorgePolicyInterface {

  protected $eventPHID;
  protected $inviteePHID;
  protected $inviterPHID;
  protected $status;
  protected $availability = self::AVAILABILITY_DEFAULT;

  const STATUS_INVITED = 'invited';
  const STATUS_ATTENDING = 'attending';
  const STATUS_DECLINED = 'declined';
  const STATUS_UNINVITED = 'uninvited';

  const AVAILABILITY_DEFAULT = 'default';
  const AVAILABILITY_AVAILABLE = 'available';
  const AVAILABILITY_BUSY = 'busy';
  const AVAILABILITY_AWAY = 'away';

  public static function initializeNewCalendarEventInvitee(
    PhorgeUser $actor, $event) {
    return id(new PhorgeCalendarEventInvitee())
      ->setInviterPHID($actor->getPHID())
      ->setStatus(self::STATUS_INVITED)
      ->setEventPHID($event->getPHID());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'status' => 'text64',
        'availability' => 'text64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_event' => array(
          'columns' => array('eventPHID', 'inviteePHID'),
          'unique' => true,
        ),
        'key_invitee' => array(
          'columns' => array('inviteePHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function isAttending() {
    return ($this->getStatus() == self::STATUS_ATTENDING);
  }

  public function isUninvited() {
    if ($this->getStatus() == self::STATUS_UNINVITED) {
      return true;
    } else {
      return false;
    }
  }

  public function getDisplayAvailability(PhorgeCalendarEvent $event) {
    switch ($this->getAvailability()) {
      case self::AVAILABILITY_DEFAULT:
      case self::AVAILABILITY_BUSY:
        return self::AVAILABILITY_BUSY;
      case self::AVAILABILITY_AWAY:
        return self::AVAILABILITY_AWAY;
      default:
        return null;
    }
  }

  public static function getAvailabilityMap() {
    return array(
      self::AVAILABILITY_AVAILABLE => array(
        'color' => 'green',
        'name' => pht('Available'),
      ),
      self::AVAILABILITY_BUSY => array(
        'color' => 'orange',
        'name' => pht('Busy'),
      ),
      self::AVAILABILITY_AWAY => array(
        'color' => 'red',
        'name' => pht('Away'),
      ),
    );
  }

  public static function getAvailabilitySpec($const) {
    return idx(self::getAvailabilityMap(), $const, array());
  }

  public static function getAvailabilityName($const) {
    $spec = self::getAvailabilitySpec($const);
    return idx($spec, 'name', $const);
  }

  public static function getAvailabilityColor($const) {
    $spec = self::getAvailabilitySpec($const);
    return idx($spec, 'color', 'indigo');
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

}
