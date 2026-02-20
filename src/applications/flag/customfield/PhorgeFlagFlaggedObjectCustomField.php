<?php

final class PhorgeFlagFlaggedObjectCustomField extends PhabricatorCustomField {

  private $flag;

  public function getFieldKey() {
    return 'flag:flag';
  }
  public function shouldAppearInPropertyView() {
    return false;
  }

  public function shouldAppearInListView() {
    return true;
  }

  public function renderOnListItem(PHUIObjectItemView $view) {
    if (!$this->flag) {
      return;
    }
    // I'm very open to improvements in the way a Flag is displayed
    $icon = PhabricatorFlagColor::getIcon($this->flag->getColor());
    $view->addIcon($icon);
  }


  public function shouldUseStorage() {
    return true;
  }

  public function setValueFromStorage($value) {
    $this->flag = $value;
    return $this;
  }

  // The parent function is defined to return a PhabricatorCustomFieldStorage,
  // but that assumes a DTO with a particular form; That doesn't apply here.
  // Maybe the function needs to be re-defined with a suitable interface.
  // For now, PhorgeFlagFlaggedObjectFieldStorage just duck-types into the
  // right shape.
  public function newStorageObject() {
    return id(new PhorgeFlagFlaggedObjectFieldStorage())
      ->setViewer($this->getViewer());
  }

}
