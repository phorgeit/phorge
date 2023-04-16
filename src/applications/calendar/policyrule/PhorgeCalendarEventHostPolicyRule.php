<?php

final class PhorgeCalendarEventHostPolicyRule
  extends PhorgePolicyRule {

  public function getObjectPolicyKey() {
    return 'calendar.event.host';
  }

  public function getObjectPolicyName() {
    return pht('Event Host');
  }

  public function getPolicyExplanation() {
    return pht('The host of this event can take this action.');
  }

  public function getRuleDescription() {
    return pht('event host');
  }

  public function canApplyToObject(PhorgePolicyInterface $object) {
    return ($object instanceof PhorgeCalendarEvent);
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {

    $viewer_phid = $viewer->getPHID();
    if (!$viewer_phid) {
      return false;
    }

    return ($object->getHostPHID() == $viewer_phid);
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_NONE;
  }

}
