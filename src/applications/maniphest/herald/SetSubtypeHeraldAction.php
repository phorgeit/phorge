<?php

final class SetSubtypeHeraldAction extends HeraldAction {
  const ACTIONCONST = 'maniphest.subtype';
  const DO_SUBTYPE = 'do.subtype';

  public function getActionGroupKey() {
    return HeraldApplicationActionGroup::ACTIONGROUPKEY;
  }

  public function supportsObject($object) {
    return $object instanceof ManiphestTask;
  }

  public function supportsRuleType($rule_type) {
    return $rule_type == HeraldRuleTypeConfig::RULE_TYPE_GLOBAL;
  }

  public function getActionKey() {
    return self::ACTIONCONST;
  }

  public function getHeraldActionName() {
    return pht('Change subtype to');
  }

  public function renderActionDescription($value) {
    $type = head($value);
    return pht('Change subtype to "%s"', $type);
  }

  public function getHeraldActionStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return id(new ManiphestTaskSubtypeDatasource())
      ->setLimit(1);
  }

  protected function getDatasourceValueMap() {
    $map = id(new ManiphestTask())->newEditEngineSubtypeMap();
    return $map->getSubtypes();
  }

  public function applyEffect($object,  HeraldEffect $effect) {
    $new_subtype = head($effect->getTarget());

    $adapter = $this->getAdapter();
    $adapter->queueTransaction(id(new ManiphestTransaction())
      ->setTransactionType(PhabricatorTransactions::TYPE_SUBTYPE)
      ->setNewValue($new_subtype));

    $this->logEffect(self::DO_SUBTYPE, $new_subtype);
  }

  protected function getActionEffectMap() {
    return array(
      self::DO_SUBTYPE => array(
        'icon' => 'fa-pencil',
        'color' => 'green',
        'name' => pht('Changed Subtype'),
      ),
    );
  }

  protected function renderActionEffectDescription($type, $data) {
    switch ($type) {
      case self::DO_SUBTYPE:
        return pht('Change subtype to "%s."', $data);
    }
  }

}
