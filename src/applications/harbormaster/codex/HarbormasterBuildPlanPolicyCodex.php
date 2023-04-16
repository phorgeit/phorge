<?php

final class HarbormasterBuildPlanPolicyCodex
  extends PhorgePolicyCodex {

  public function getPolicySpecialRuleDescriptions() {
    $object = $this->getObject();
    $run_with_view = $object->canRunWithoutEditCapability();

    $rules = array();

    $rules[] = $this->newRule()
      ->setCapabilities(
        array(
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->setIsActive(!$run_with_view)
      ->setDescription(
        pht(
          'You must have edit permission on this build plan to pause, '.
          'abort, resume, or restart it.'));

    $rules[] = $this->newRule()
      ->setCapabilities(
        array(
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->setIsActive(!$run_with_view)
      ->setDescription(
        pht(
          'You must have edit permission on this build plan to run it '.
          'manually.'));

    return $rules;
  }


}
