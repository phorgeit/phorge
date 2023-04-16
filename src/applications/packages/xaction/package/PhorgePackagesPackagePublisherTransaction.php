<?php

final class PhorgePackagesPackagePublisherTransaction
  extends PhorgePackagesPackageTransactionType {

  const TRANSACTIONTYPE = 'packages.package.publisher';

  public function generateOldValue($object) {
    return $object->getPublisherPHID();
  }

  public function applyInternalEffects($object, $value) {
    $object->setPublisherPHID($value);
  }

  public function validateTransactions($object, array $xactions) {
    $errors = array();

    $current_value = $object->getPublisherPHID();
    if ($this->isEmptyTextTransaction($current_value, $xactions)) {
      $errors[] = $this->newRequiredError(
        pht(
          'You must select a publisher when creating a package.'));
      return $errors;
    }

    if (!$this->isNewObject()) {
      foreach ($xactions as $xaction) {
        $errors[] = $this->newInvalidError(
          pht('Once a package is created, its publisher can not be changed.'),
          $xaction);
      }
    }

    $viewer = $this->getActor();
    foreach ($xactions as $xaction) {
      $publisher_phid = $xaction->getNewValue();

      $publisher = id(new PhorgePackagesPublisherQuery())
        ->setViewer($viewer)
        ->withPHIDs(array($publisher_phid))
        ->setRaisePolicyExceptions(false)
        ->requireCapabilities(
          array(
            PhorgePolicyCapability::CAN_VIEW,
            PhorgePolicyCapability::CAN_EDIT,
          ))
        ->executeOne();

      if (!$publisher) {
        $errors[] = $this->newInvalidError(
          pht(
            'Publisher "%s" is invalid: the publisher must exist and you '.
            'must have permission to edit it in order to create a new '.
            'package.',
            $publisher_phid),
          $xaction);
        continue;
      }

      $object->attachPublisher($publisher);
    }

    return $errors;
  }

}
