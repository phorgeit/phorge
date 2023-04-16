<?php

final class PhortuneAccountEmailEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgePhortuneApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Phortune Account Emails');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this account email.', $author);
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();

    $errors[] = new PhorgeApplicationTransactionValidationError(
      PhortuneAccountEmailAddressTransaction::TRANSACTIONTYPE,
      pht('Duplicate'),
      pht(
        'The email address "%s" is already attached to this account.',
        $object->getAddress()),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }

}
