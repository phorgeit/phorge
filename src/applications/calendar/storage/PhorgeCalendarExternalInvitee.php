<?php

final class PhorgeCalendarExternalInvitee
  extends PhorgeCalendarDAO
  implements PhorgePolicyInterface {

  protected $name;
  protected $nameIndex;
  protected $uri;
  protected $parameters = array();
  protected $sourcePHID;

  public static function initializeNewCalendarEventInvitee(
    PhorgeUser $actor, $event) {
    return id(new PhorgeCalendarEventInvitee())
      ->setInviterPHID($actor->getPHID())
      ->setStatus(PhorgeCalendarEventInvitee::STATUS_INVITED)
      ->setEventPHID($event->getPHID());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'parameters' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text',
        'nameIndex' => 'bytes12',
        'uri' => 'text',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_name' => array(
          'columns' => array('nameIndex'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function getPHIDType() {
    return PhorgeCalendarExternalInviteePHIDType::TYPECONST;
  }

  public function save() {
    $this->nameIndex = PhorgeHash::digestForIndex($this->getName());
    return parent::save();
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
