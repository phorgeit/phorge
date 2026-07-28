<?php

final class PhorgeGlobalPolicyRule extends PhabricatorPolicyRule {

  public function getRuleDescription() {
    return pht('Implementation of the Global rules');
  }

  public function applyRule(
    PhabricatorUser $viewer,
    $value,
    PhabricatorPolicyInterface $object) {

    switch ($value) {
      case PhabricatorPolicies::POLICY_PUBLIC:
        if (PhabricatorEnv::getEnvConfig('policy.allow-public')) {
          return true;
        }
        // If the object is set to "public" but that policy is disabled for this
        // install, restrict the policy to "user":
      case PhabricatorPolicies::POLICY_USER:
        return (bool)$viewer->getPHID();

      case PhabricatorPolicies::POLICY_ADMIN:
        return $viewer->getIsAdmin();

      case PhabricatorPolicies::POLICY_NOONE:
      default:
        return false;
    }
  }

  public function shouldHideFromUI() {
    return true;
  }

}
