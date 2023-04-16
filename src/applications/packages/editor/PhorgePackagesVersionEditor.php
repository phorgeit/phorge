<?php

final class PhorgePackagesVersionEditor
  extends PhorgePackagesEditor {

  public function getEditorObjectsDescription() {
    return pht('Package Versions');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this version.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  protected function getMailTo(PhorgeLiskDAO $object) {
    return array();
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();
    $errors[] = new PhorgeApplicationTransactionValidationError(
      PhorgePackagesVersionNameTransaction::TRANSACTIONTYPE,
      pht('Duplicate'),
      pht(
        'The version "%s" already exists for this package. Each version '.
        'must have a unique name.',
        $object->getName()),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }

}
