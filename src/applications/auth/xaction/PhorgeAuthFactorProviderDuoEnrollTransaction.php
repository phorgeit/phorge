<?php

final class PhorgeAuthFactorProviderDuoEnrollTransaction
  extends PhorgeAuthFactorProviderTransactionType {

  const TRANSACTIONTYPE = 'duo.enroll';

  public function generateOldValue($object) {
    $key = PhorgeDuoAuthFactor::PROP_ENROLL;
    return $object->getAuthFactorProviderProperty($key);
  }

  public function applyInternalEffects($object, $value) {
    $key = PhorgeDuoAuthFactor::PROP_ENROLL;
    $object->setAuthFactorProviderProperty($key, $value);
  }

  public function getTitle() {
    return pht(
      '%s changed the enrollment policy for this provider from %s to %s.',
      $this->renderAuthor(),
      $this->renderOldValue(),
      $this->renderNewValue());
  }

}
