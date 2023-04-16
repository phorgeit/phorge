<?php

final class PhorgePackagesPackageEditor
  extends PhorgePackagesEditor {

  public function getEditorObjectsDescription() {
    return pht('Package Packages');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this package.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
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
      PhorgePackagesPackageKeyTransaction::TRANSACTIONTYPE,
      pht('Duplicate'),
      pht(
        'The package key "%s" is already in use by another package provided '.
        'by this publisher.',
        $object->getPackageKey()),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }

}
