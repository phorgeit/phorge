<?php

final class DiffusionAuditorsAddAuditorsHeraldAction
  extends DiffusionAuditorsHeraldAction {

  const ACTIONCONST = 'diffusion.auditors.add';

  public function getHeraldActionName() {
    return pht('Add auditors');
  }

  // hide "Add auditors" Herald action if Audit not installed
  public function supportsRuleType($rule_type) {
    if (id(new PhabricatorAuditApplication())->isInstalled()) {
      return ($rule_type != HeraldRuleTypeConfig::RULE_TYPE_PERSONAL);
    } else {
      return false;
    }
  }

  public function applyEffect($object, HeraldEffect $effect) {
    $rule = $effect->getRule();
    return $this->applyAuditors($effect->getTarget(), $rule);
  }

  public function getHeraldActionStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new DiffusionAuditorDatasource();
  }

  public function renderActionDescription($value) {
    return pht('Add auditors: %s.', $this->renderHandleList($value));
  }

  public function getPHIDsAffectedByAction(HeraldActionRecord $record) {
    return $record->getTarget();
  }

}
