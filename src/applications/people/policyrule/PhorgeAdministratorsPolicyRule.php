<?php

final class PhorgeAdministratorsPolicyRule extends PhorgePolicyRule {

  public function getRuleDescription() {
    return pht('administrators');
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {
    return $viewer->getIsAdmin();
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_NONE;
  }

}
