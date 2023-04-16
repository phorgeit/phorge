<?php

abstract class PhorgeFlagHeraldAction
  extends HeraldAction {

  public function getActionGroupKey() {
    return HeraldSupportActionGroup::ACTIONGROUPKEY;
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeFlaggableInterface);
  }

  public function supportsRuleType($rule_type) {
    return ($rule_type === HeraldRuleTypeConfig::RULE_TYPE_PERSONAL);
  }

}
