<?php

final class PhorgeLabelProfileMenuItem
  extends PhorgeProfileMenuItem {

  const MENUITEMKEY = 'label';
  const FIELD_NAME = 'name';

  public function getMenuItemTypeIcon() {
    return 'fa-tag';
  }

  public function getMenuItemTypeName() {
    return pht('Label');
  }

  public function canAddToObject($object) {
    return true;
  }

  public function getDisplayName(
    PhorgeProfileMenuItemConfiguration $config) {
    return $this->getLabelName($config);
  }

  public function buildEditEngineFields(
    PhorgeProfileMenuItemConfiguration $config) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey(self::FIELD_NAME)
        ->setLabel(pht('Name'))
        ->setIsRequired(true)
        ->setValue($this->getLabelName($config)),
    );
  }

  private function getLabelName(
    PhorgeProfileMenuItemConfiguration $config) {
    return $config->getMenuItemProperty('name');
  }

  protected function newMenuItemViewList(
    PhorgeProfileMenuItemConfiguration $config) {

    $name = $this->getLabelName($config);

    $item = $this->newItemView()
      ->setName($name)
      ->setIsLabel(true);

    return array(
      $item,
    );
  }

  public function validateTransactions(
    PhorgeProfileMenuItemConfiguration $config,
    $field_key,
    $value,
    array $xactions) {

    $viewer = $this->getViewer();
    $errors = array();

    if ($field_key == self::FIELD_NAME) {
      if ($this->isEmptyTransaction($value, $xactions)) {
       $errors[] = $this->newRequiredError(
         pht('You must choose a label name.'),
         $field_key);
      }
    }

    return $errors;
  }
}
