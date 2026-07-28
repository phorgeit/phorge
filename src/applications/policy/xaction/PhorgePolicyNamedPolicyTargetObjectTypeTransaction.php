<?php

final class PhorgePolicyNamedPolicyTargetObjectTypeTransaction
extends PhorgePolicyNamedPolicyTransactionType {

  const TRANSACTIONTYPE = 'namedpolicy:targetobjecttype';

  public function generateOldValue($object) {
    return $object->getTargetObjectType();
  }

  public function applyInternalEffects($object, $value) {
    $object->setTargetObjectType($value);
  }

  public function getTitle() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    return pht(
      '%s changed the objects type this policy is applicable to from %s to %s',
      $this->renderAuthor(),
      $this->renderValue($this->showPHIDType($old)),
      $this->renderValue($this->showPHIDType($new)));
  }

  public function getTitleForFeed() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    return pht(
      '%s changed the objects type policy %s is applicable to from %s to %s',
      $this->renderAuthor(),
      $this->renderObject(),
      $this->renderValue($this->showPHIDType($old)),
      $this->renderValue($this->showPHIDType($new)));
  }

  private function showPHIDType($value) {
    if (!phutil_nonempty_string($value)) {
      return pht('All object types');
    }

    $type_objects = PhabricatorPHIDType::getTypes(array($value));
    $phid_type = idx($type_objects, $value);
    if (!$phid_type) {
      return pht('Invalid value `%s`', $value);
    }
    return $phid_type->getTypeName();


  }


  public function validateTransactions($object, array $xactions) {
    $errors = array();

    // TODO

    return $errors;
  }

}
