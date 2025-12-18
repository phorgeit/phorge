<?php

final class PhabricatorCalendarEventInvitee extends PhabricatorCalendarDAO
  implements PhabricatorPolicyInterface {

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
    PhabricatorUser $actor, $event) {
    return id(new PhabricatorCalendarEventInvitee())
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

  public function getDisplayAvailability(PhabricatorCalendarEvent $event) {
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

  /**
   * Import the invitee availability from the Time Transparency
   * field in an ICS calendar event as per RFC 5545 section 3.8.2.7.
   * @param mixed $time_transp Time transparency like 'OPAQUE'
   *                          or 'TRANSPARENT' or null.
   * @return void
   */
  public function importAvailabilityFromTimeTransparency($time_transp) {
    // How to understand RFC 5545 suburbs. Example conversation:
    //  "Hey dude
    //   I'm a bit *opaque* on this event so I'm not *transparent*"
    // Means:
    //  "Good morning Sir,
    //   I'm a bit *busy* on this business so I'm not *available*"
    static $transparency_2_availability = array(
      'OPAQUE'      => self::AVAILABILITY_BUSY,
      'TRANSPARENT' => self::AVAILABILITY_AVAILABLE,
    );

    // Note that idx($array, $key) likes a null $key.
    $availability = idx($transparency_2_availability, $time_transp);
    if ($availability) {
      $this->setAvailability($availability);
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


/* -(  PhabricatorPolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        return PhabricatorPolicies::getMostOpenPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return false;
  }

}
