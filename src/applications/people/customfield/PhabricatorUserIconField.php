<?php

final class PhabricatorUserIconField
  extends PhabricatorUserCustomField {

  private $value;

  public function getFieldKey() {
    return 'user:icon';
  }

  public function getModernFieldKey() {
    return 'icon';
  }

  public function getFieldKeyForConduit() {
    return $this->getModernFieldKey();
  }

  public function getFieldName() {
    return pht('Icon');
  }

  public function getFieldDescription() {
    return pht('User icon to accompany their title.');
  }

  public function canDisableField() {
    return false;
  }

  public function shouldAppearInApplicationTransactions() {
    return true;
  }

  public function shouldAppearInEditView() {
    return true;
  }

  public function readValueFromObject(PhabricatorCustomFieldInterface $object) {
    $this->value = $object->loadUserProfile()->getIcon();
  }

  public function getOldValueForApplicationTransactions() {
    return $this->getObject()->loadUserProfile()->getIcon();
  }

  public function getNewValueForApplicationTransactions() {
    return $this->value;
  }

  public function applyApplicationTransactionInternalEffects(
    PhabricatorApplicationTransaction $xaction) {
    $this->getObject()->loadUserProfile()->setIcon($xaction->getNewValue());
  }

  public function readValueFromRequest(AphrontRequest $request) {
    $this->value = $request->getStr($this->getFieldKey());
  }

  public function setValueFromStorage($value) {
    $this->value = $value;
    return $this;
  }

  public function renderEditControl(array $handles) {
    return id(new PHUIFormIconSetControl())
      ->setName($this->getFieldKey())
      ->setValue($this->value)
      ->setLabel($this->getFieldName())
      ->setIconSet(new PhabricatorPeopleIconSet());
  }

  public function shouldAppearInConduitTransactions() {
    return true;
  }

  protected function newConduitEditParameterType() {
    return new ConduitStringParameterType();
  }

  public function validateApplicationTransactions(
    PhabricatorApplicationTransactionEditor $editor,
    $type,
    array $xactions) {

    $errors = parent::validateApplicationTransactions(
      $editor,
      $type,
      $xactions);

    foreach ($xactions as $xaction) {
      $new_icon = $xaction->getNewValue();
      if (!PhabricatorPeopleIconSet::getIconName($new_icon)) {
        $errors[] = new PhabricatorApplicationTransactionValidationError(
          $type,
          pht('Invalid'),
          pht(
            'Value for "%s" is invalid: "%s".',
            $this->getFieldName(),
            $new_icon));
        break;
      }
    }

    return $errors;
  }

}
