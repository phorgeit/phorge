<?php

final class PhorgeCoreSubtypeTransaction
  extends PhabricatorModularTransactionType {

  const TRANSACTIONTYPE = 'core:subtype';

  public function generateOldValue($object) {
    return $object->getEditEngineSubtype();
  }

  public function getTitle() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    return pht(
      '%s changed the subtype of this object from "%s" to "%s".',
      $this->renderAuthor(),
      $this->renderSubtypeName($old),
      $this->renderSubtypeName($new));
  }

  public function getTitleForFeed() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();
    return pht(
      '%s changed the subtype of %s from "%s" to "%s".',
      $this->renderAuthor(),
      $this->renderObject(),
      $this->renderSubtypeName($old),
      $this->renderSubtypeName($new));
  }

  public function applyInternalEffects($object, $value) {
    $object->setEditEngineSubtype($value);
  }

  public function shouldHide() {
    return $this->isCreateTransaction();
  }

  private function renderSubtypeName($value) {
    $object = $this->getObject();
    $map = $object->newEditEngineSubtypeMap();

    if (!$map->isValidSubtype($value)) {
      return $value;
    }

    return $map->getSubtype($value)->getName();
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    $map = $object->newEditEngineSubtypeMap();
    $old = $object->getEditEngineSubtype();
    foreach ($xactions as $xaction) {
      $new = $xaction->getNewValue();

      if ($old == $new) {
        continue;
      }

      if (!$map->isValidSubtype($new)) {
        $errors[] = $this->newInvalidError(
          pht('The subtype "%s" is not a valid subtype.', $new),
          $xaction);
        continue;
      }
    }

    return $errors;
  }

}
