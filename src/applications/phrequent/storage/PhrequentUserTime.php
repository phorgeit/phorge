<?php

final class PhrequentUserTime extends PhrequentDAO
  implements PhorgePolicyInterface {

  protected $userPHID;
  protected $objectPHID;
  protected $note;
  protected $dateStarted;
  protected $dateEnded;

  private $preemptingEvents = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'objectPHID' => 'phid?',
        'note' => 'text?',
        'dateStarted' => 'epoch',
        'dateEnded' => 'epoch?',
      ),
    ) + parent::getConfiguration();
  }

  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    $policy = PhorgePolicies::POLICY_NOONE;

    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        // Since it's impossible to perform any meaningful computations with
        // time if a user can't view some of it, visibility on tracked time is
        // unrestricted. If we eventually lock it down, it should be per-user.
        // (This doesn't mean that users can see tracked objects.)
        return PhorgePolicies::getMostOpenPolicy();
    }

    return $policy;
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return ($viewer->getPHID() == $this->getUserPHID());
  }


  public function describeAutomaticCapability($capability) {
    return null;
  }

  public function attachPreemptingEvents(array $events) {
    $this->preemptingEvents = $events;
    return $this;
  }

  public function getPreemptingEvents() {
    return $this->assertAttached($this->preemptingEvents);
  }

  public function isPreempted() {
    if ($this->getDateEnded() !== null) {
      return false;
    }
    foreach ($this->getPreemptingEvents() as $event) {
      if ($event->getDateEnded() === null &&
          $event->getObjectPHID() != $this->getObjectPHID()) {
        return true;
      }
    }
    return false;
  }

}
