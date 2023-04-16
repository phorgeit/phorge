<?php

final class PhorgeDashboardStatusTransaction
  extends PhorgeDashboardTransactionType {

  const TRANSACTIONTYPE = 'dashboard:status';

  public function generateOldValue($object) {
    return $object->getStatus();
  }

  public function applyInternalEffects($object, $value) {
    $object->setStatus($value);
  }

  public function getTitle() {
    $new = $this->getNewValue();

    switch ($new) {
      case PhorgeDashboard::STATUS_ACTIVE:
        return pht(
          '%s activated this dashboard.',
          $this->renderAuthor());
      case PhorgeDashboard::STATUS_ARCHIVED:
        return pht(
          '%s archived this dashboard.',
          $this->renderAuthor());
    }
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    $valid_statuses = PhorgeDashboard::getStatusNameMap();

    $old_value = $object->getStatus();
    foreach ($xactions as $xaction) {
      $new_value = $xaction->getNewValue();

      if ($new_value === $old_value) {
        continue;
      }

      if (!isset($valid_statuses[$new_value])) {
        $errors[] = $this->newInvalidError(
          pht(
            'Status "%s" is not valid. Supported status constants are: %s.',
            $new_value,
            implode(', ', array_keys($valid_statuses))),
          $xaction);
        continue;
      }
    }

    return $errors;
  }


}
