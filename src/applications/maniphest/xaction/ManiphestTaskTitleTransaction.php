<?php

final class ManiphestTaskTitleTransaction
  extends ManiphestTaskTransactionType {

  const TRANSACTIONTYPE = 'title';
  private $maximumTaskTitleLength = 255;

  public function generateOldValue($object) {
    return $object->getTitle();
  }

  public function applyInternalEffects($object, $value) {
    $object->setTitle($value);
  }

  public function getActionStrength() {
    return 140;
  }

  public function getActionName() {
    $old = $this->getOldValue();

    if (!strlen($old)) {
      return pht('Created');
    }

    return pht('Retitled');
  }

  public function getTitle() {
    $old = $this->getOldValue();

    if (!strlen($old)) {
      return pht(
        '%s created this task.',
        $this->renderAuthor());
    }

    return pht(
      '%s renamed this task from %s to %s.',
      $this->renderAuthor(),
      $this->renderOldValue(),
      $this->renderNewValue());

  }

  public function getTitleForFeed() {
    $old = $this->getOldValue();
    if ($old === null) {
      return pht(
        '%s created %s.',
        $this->renderAuthor(),
        $this->renderObject());
    }

    return pht(
      '%s renamed %s from %s to %s.',
      $this->renderAuthor(),
      $this->renderObject(),
      $this->renderOldValue(),
      $this->renderNewValue());
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    // If the user is acting via "Bulk Edit" or another workflow which
    // continues on missing fields, they may be applying a transaction which
    // removes the task title. Mark these transactions as invalid first,
    // then flag the missing field error if we don't find any more specific
    // problems.

    foreach ($xactions as $xaction) {
      $new_value = $xaction->getNewValue();
      $new_value = trim($new_value); // Strip surrounding whitespace
      $xaction->setNewValue($new_value);
      if (!strlen($new_value)) {
        $errors[] = $this->newInvalidError(
          pht('Tasks must have a title.'),
          $xaction);
        continue;
      }
      if (mb_strlen($new_value) > $this->maximumTaskTitleLength) {
        $errors[] = $this->newInvalidError(
          pht('Task title cannot exceed %d characters.',
            $this->maximumTaskTitleLength),
          $xaction);
        continue;
      }
    }

    if (!$errors) {
      if ($this->isEmptyTextTransaction($object->getTitle(), $xactions)) {
        $errors[] = $this->newRequiredError(
          pht('Tasks must have a title.'));
      }
    }

    return $errors;
  }

  public function getTransactionTypeForConduit($xaction) {
    return 'title';
  }

  public function getFieldValuesForConduit($xaction, $data) {
    return array(
      'old' => $xaction->getOldValue(),
      'new' => $xaction->getNewValue(),
    );
  }

}
