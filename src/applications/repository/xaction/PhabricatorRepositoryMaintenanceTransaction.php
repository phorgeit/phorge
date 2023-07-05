<?php

final class PhabricatorRepositoryMaintenanceTransaction
  extends PhabricatorRepositoryTransactionType {

  const TRANSACTIONTYPE = 'maintenance';

  public function generateOldValue($object) {
    return $object->getReadOnlyMessage();
  }

  public function applyInternalEffects($object, $value) {
    if ($value === null) {
      $object
        ->setReadOnly(false)
        ->setReadOnlyMessage(null);
    } else {
      $object
        ->setReadOnly(true)
        ->setReadOnlyMessage($value);
    }
  }

  public function getTitle() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    $old_nonempty = phutil_nonempty_string($old);
    $new_nonempty = phutil_nonempty_string($new);

    if ($old_nonempty && !$new_nonempty) {
      return pht(
        '%s took this repository out of maintenance mode.',
        $this->renderAuthor());
    } else if (!$old_nonempty && $new_nonempty) {
      return pht(
        '%s put this repository into maintenance mode.',
        $this->renderAuthor());
    } else {
      return pht(
        '%s updated the maintenance message for this repository.',
        $this->renderAuthor());
    }
  }

}
