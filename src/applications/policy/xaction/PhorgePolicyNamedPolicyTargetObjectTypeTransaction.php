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
    $viewer = $this->getActor();
    $installed_types = PhabricatorPHIDType::getAllInstalledTypes($viewer);
    $valid_types = implode(', ', array_keys($installed_types));

    foreach ($xactions as $xaction) {
      $new_value = $xaction->getNewValue();

      if (phutil_nonempty_string($new_value) &&
          !array_key_exists($new_value, $installed_types)) {
        $errors[] = $this->newInvalidError(
          pht(
            'Invalid Target Object Type. Valid types are: %s.',
            $valid_types));
      }
    }

    return $errors;
  }

}
