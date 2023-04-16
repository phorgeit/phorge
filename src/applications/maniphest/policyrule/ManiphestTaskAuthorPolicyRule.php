<?php

final class ManiphestTaskAuthorPolicyRule
  extends PhorgePolicyRule {

  public function getObjectPolicyKey() {
    return 'maniphest.author';
  }

  public function getObjectPolicyName() {
    return pht('Task Author');
  }

  public function getPolicyExplanation() {
    return pht('The author of this task can take this action.');
  }

  public function getRuleDescription() {
    return pht('task author');
  }

  public function canApplyToObject(PhorgePolicyInterface $object) {
    return ($object instanceof ManiphestTask);
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {

    $viewer_phid = $viewer->getPHID();
    if (!$viewer_phid) {
      return false;
    }

    return ($object->getAuthorPHID() == $viewer_phid);
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_NONE;
  }

}
