<?php

final class PhorgeProjectColumnStatusTransaction
  extends PhorgeProjectColumnTransactionType {

  const TRANSACTIONTYPE = 'project:col:status';

  public function generateOldValue($object) {
    return $object->getStatus();
  }

  public function applyInternalEffects($object, $value) {
    $object->setStatus($value);
  }

  public function applyExternalEffects($object, $value) {
    // Update the trigger usage index, which cares about whether columns are
    // active or not.
    $trigger_phid = $object->getTriggerPHID();
    if ($trigger_phid) {
      PhorgeSearchWorker::queueDocumentForIndexing($trigger_phid);
    }
  }

  public function getTitle() {
    $new = $this->getNewValue();

    switch ($new) {
      case PhorgeProjectColumn::STATUS_ACTIVE:
        return pht(
          '%s unhid this column.',
          $this->renderAuthor());
      case PhorgeProjectColumn::STATUS_HIDDEN:
        return pht(
          '%s hid this column.',
          $this->renderAuthor());
    }
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    $map = array(
      PhorgeProjectColumn::STATUS_ACTIVE,
      PhorgeProjectColumn::STATUS_HIDDEN,
    );
    $map = array_fuse($map);

    foreach ($xactions as $xaction) {
      $value = $xaction->getNewValue();
      if (!isset($map[$value])) {
        $errors[] = $this->newInvalidError(
          pht(
            'Column status "%s" is unrecognized, valid statuses are: %s.',
            $value,
            implode(', ', array_keys($map))),
          $xaction);
      }
    }

    return $errors;
  }

}
