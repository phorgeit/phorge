<?php

final class PhorgeAuthContactNumberEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Contact Numbers');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this contact number.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();
    $errors[] = new PhorgeApplicationTransactionValidationError(
      PhorgeAuthContactNumberNumberTransaction::TRANSACTIONTYPE,
      pht('Duplicate'),
      pht('This contact number is already in use.'),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }


}
