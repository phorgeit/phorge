<?php

final class PhorgeProjectsPolicyRule
  extends PhorgeProjectsBasePolicyRule {

  public function getRuleDescription() {
    return pht('members of any project');
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {

    $memberships = $this->getMemberships($viewer->getPHID());
    foreach ($value as $project_phid) {
      if (isset($memberships[$project_phid])) {
        return true;
      }
    }

    return false;
  }

  public function getRuleOrder() {
    return 200;
  }

}
