<?php

final class PhorgeCustomFieldHeraldAction extends HeraldAction {

  const ACTIONCONST = 'herald.action.custom';

  const DO_SET_FIELD = 'do.set-custom-field';

  private $customField;

  public function setCustomField(PhorgeCustomField $custom_field) {
    $this->customField = $custom_field;
    return $this;
  }

  public function getCustomField() {
    return $this->customField;
  }

  public function getActionGroupKey() {
    return PhorgeCustomFieldHeraldActionGroup::ACTIONGROUPKEY;
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeCustomFieldInterface);
  }

  public function supportsRuleType($rule_type) {
    return true;
  }

  public function getActionsForObject($object) {
    $viewer = PhorgeUser::getOmnipotentUser();
    $role = PhorgeCustomField::ROLE_HERALDACTION;

    $field_list = PhorgeCustomField::getObjectFields($object, $role)
      ->setViewer($viewer)
      ->readFieldsFromStorage($object);

    $map = array();
    foreach ($field_list->getFields() as $field) {
      $key = $field->getFieldKey();
      $map[$key] = id(new self())
        ->setCustomField($field);
    }

    return $map;
  }

  public function applyEffect($object, HeraldEffect $effect) {
    $field = $this->getCustomField();
    $value = $effect->getTarget();
    $adapter = $this->getAdapter();

    $old_value = $field->getOldValueForApplicationTransactions();
    $new_value = id(clone $field)
      ->setValueFromApplicationTransactions($value)
      ->getValueForStorage();

    $xaction = $adapter->newTransaction()
      ->setTransactionType(PhorgeTransactions::TYPE_CUSTOMFIELD)
      ->setMetadataValue('customfield:key', $field->getFieldKey())
      ->setOldValue($old_value)
      ->setNewValue($new_value);

    $adapter->queueTransaction($xaction);

    $this->logEffect(self::DO_SET_FIELD, $value);
  }

  public function getHeraldActionName() {
    return $this->getCustomField()->getHeraldActionName();
  }

  public function getHeraldActionStandardType() {
    return $this->getCustomField()->getHeraldActionStandardType();
  }

  protected function getDatasource() {
    return $this->getCustomField()->getHeraldActionDatasource();
  }

  public function renderActionDescription($value) {
    return $this->getCustomField()->getHeraldActionDescription($value);
  }

  protected function getActionEffectMap() {
    return array(
      self::DO_SET_FIELD => array(
        'icon' => 'fa-pencil',
        'color' => 'green',
        'name' => pht('Set Field Value'),
      ),
    );
  }

  protected function renderActionEffectDescription($type, $data) {
    switch ($type) {
      case self::DO_SET_FIELD:
        return $this->getCustomField()->getHeraldActionEffectDescription($data);
    }
  }


}
