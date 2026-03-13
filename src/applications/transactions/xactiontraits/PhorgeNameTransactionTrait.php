<?php

/**
 * Short-hand for writing transactions to edit string `$name` field.
 */
trait PhorgeNameTransactionTrait {

  use PhorgeTransactionObjectNameTrait;

  public function generateOldValue($object) {
    return $object->getName();
  }

  public function applyInternalEffects($object, $value) {
    $object->setName($value);
  }

  public function getTitle() {
    if (strlen($this->getOldValue())) {
      return pht(
        '%s renamed this %s from %s to %s.',
        $this->renderAuthor(),
        $this->renderObjectType(),
        $this->renderOldValue(),
        $this->renderNewValue());
      } else {
        return pht(
        '%s created %s %s.',
        $this->renderAuthor(),
        $this->renderObjectType(),
        $this->renderNewValue());
    }
  }

  public function getTitleForFeed() {
    $old = $this->getOldValue();
    if ($old === null) {
      return pht(
        '%s created %s %s.',
        $this->renderAuthor(),
        $this->renderObjectType(),
        $this->renderObject());
    } else {
      return pht(
        '%s renamed %s %s from %s to %s.',
        $this->renderAuthor(),
        $this->renderObjectType(),
        $this->renderObject(),
        $this->renderOldValue(),
        $this->renderNewValue());
    }
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    if ($this->isEmptyTextTransaction($object->getName(), $xactions)) {
      $errors[] = $this->newRequiredError(
        pht('Name is required.'));
    }

    $max_length = $object->getColumnMaximumByteLength('name');
    if ($max_length !== null) {
      foreach ($xactions as $xaction) {
        $new_value = $xaction->getNewValue();

        $new_length = strlen($new_value);
        if ($new_length > $max_length) {
          $errors[] = $this->newInvalidError(
            pht(
              'Name for a %s can be no longer than %s characters.',
              $this->renderObjectType(),
              new PhutilNumber($max_length)));
        }
      }
    }

    return $errors;
  }

}
