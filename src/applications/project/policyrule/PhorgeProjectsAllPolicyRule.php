<?php

final class PhorgeProjectsAllPolicyRule
  extends PhorgeProjectsBasePolicyRule {

  public function getRuleDescription() {
    return pht('members of all projects');
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {

    $memberships = $this->getMemberships($viewer->getPHID());
    foreach ($value as $project_phid) {
      if (empty($memberships[$project_phid])) {
        return false;
      }
    }

    return true;
  }

  public function getRuleOrder() {
    return 205;
  }

}
