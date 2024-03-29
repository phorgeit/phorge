<?php

final class DiffusionAuditorsAddSelfHeraldAction
  extends DiffusionAuditorsHeraldAction {

  const ACTIONCONST = 'diffusion.auditors.self.add';

  public function getHeraldActionName() {
    return pht('Add me as an auditor');
  }

  // hide "Add me as an auditor" Herald action if Audit not installed
  public function supportsRuleType($rule_type) {
    if (id(new PhabricatorAuditApplication())->isInstalled()) {
      return ($rule_type == HeraldRuleTypeConfig::RULE_TYPE_PERSONAL);
    } else {
      return false;
    }
  }

  public function applyEffect($object, HeraldEffect $effect) {
    $rule = $effect->getRule();
    $phid = $rule->getAuthorPHID();
    return $this->applyAuditors(array($phid), $rule);
  }

  public function getHeraldActionStandardType() {
    return self::STANDARD_NONE;
  }

  public function renderActionDescription($value) {
    return pht('Add rule author as auditor.');
  }

}
