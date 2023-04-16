<?php

final class ConpherenceThreadMembersPolicyRule
  extends PhorgePolicyRule {

  public function getObjectPolicyKey() {
    return 'conpherence.members';
  }

  public function getObjectPolicyName() {
    return pht('Room Participants');
  }

  public function getPolicyExplanation() {
    return pht('Participants in this room can take this action.');
  }

  public function getRuleDescription() {
    return pht('room participants');
  }

  public function getObjectPolicyIcon() {
    return 'fa-comments';
  }

  public function canApplyToObject(PhorgePolicyInterface $object) {
    return ($object instanceof ConpherenceThread);
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {
    $viewer_phid = $viewer->getPHID();
    if (!$viewer_phid) {
      return false;
    }

    return (bool)$object->getParticipantIfExists($viewer_phid);
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_NONE;
  }

}
