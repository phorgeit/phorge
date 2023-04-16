<?php

final class PhorgeMetaMTAApplicationEmailEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return pht('PhorgeMetaMTAApplication');
  }

  public function getEditorObjectsDescription() {
    return pht('Application Emails');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeMetaMTAApplicationEmailTransaction::TYPE_ADDRESS;
    $types[] = PhorgeMetaMTAApplicationEmailTransaction::TYPE_CONFIG;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_ADDRESS:
        return $object->getAddress();
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_CONFIG:
        $key = $xaction->getMetadataValue(
          PhorgeMetaMTAApplicationEmailTransaction::KEY_CONFIG);
        return $object->getConfigValue($key);
    }

    return parent::getCustomTransactionOldValue($object, $xaction);
  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_ADDRESS:
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_CONFIG:
        return $xaction->getNewValue();
    }

    return parent::getCustomTransactionNewValue($object, $xaction);
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $new = $xaction->getNewValue();

    switch ($xaction->getTransactionType()) {
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_ADDRESS:
        $object->setAddress($new);
        return;
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_CONFIG:
        $key = $xaction->getMetadataValue(
          PhorgeMetaMTAApplicationEmailTransaction::KEY_CONFIG);
        $object->setConfigValue($key, $new);
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_ADDRESS:
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_CONFIG:
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function validateTransaction(
    PhorgeLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);

    switch ($type) {
      case PhorgeMetaMTAApplicationEmailTransaction::TYPE_ADDRESS:
        foreach ($xactions as $xaction) {
          $email = $xaction->getNewValue();
          if (!strlen($email)) {
            // We'll deal with this below.
            continue;
          }

          if (!PhorgeUserEmail::isValidAddress($email)) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $type,
              pht('Invalid'),
              pht('Email address is not formatted properly.'));
            continue;
          }

          $address = new PhutilEmailAddress($email);
          if (PhorgeMailUtil::isReservedAddress($address)) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $type,
              pht('Reserved'),
              pht(
                'This email address is reserved. Choose a different '.
                'address.'));
            continue;
          }

          // See T13234. Prevent use of user email addresses as application
          // email addresses.
          if (PhorgeMailUtil::isUserAddress($address)) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $type,
              pht('In Use'),
              pht(
                'This email address is already in use by a user. Choose '.
                'a different address.'));
            continue;
          }
        }

        $missing = $this->validateIsEmptyTextField(
          $object->getAddress(),
          $xactions);

        if ($missing) {
          $error = new PhorgeApplicationTransactionValidationError(
            $type,
            pht('Required'),
            pht('You must provide an email address.'),
            nonempty(last($xactions), null));

          $error->setIsMissingFieldError(true);
          $errors[] = $error;
        }
        break;
    }

    return $errors;
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();
    $errors[] = new PhorgeApplicationTransactionValidationError(
      PhorgeMetaMTAApplicationEmailTransaction::TYPE_ADDRESS,
      pht('Duplicate'),
      pht('This email address is already in use.'),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }


}
