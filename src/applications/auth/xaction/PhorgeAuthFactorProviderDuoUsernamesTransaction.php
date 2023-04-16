<?php

final class PhorgeAuthFactorProviderDuoUsernamesTransaction
  extends PhorgeAuthFactorProviderTransactionType {

  const TRANSACTIONTYPE = 'duo.usernames';

  public function generateOldValue($object) {
    $key = PhorgeDuoAuthFactor::PROP_USERNAMES;
    return $object->getAuthFactorProviderProperty($key);
  }

  public function applyInternalEffects($object, $value) {
    $key = PhorgeDuoAuthFactor::PROP_USERNAMES;
    $object->setAuthFactorProviderProperty($key, $value);
  }

  public function getTitle() {
    return pht(
      '%s changed the username policy for this provider from %s to %s.',
      $this->renderAuthor(),
      $this->renderOldValue(),
      $this->renderNewValue());
  }

}
