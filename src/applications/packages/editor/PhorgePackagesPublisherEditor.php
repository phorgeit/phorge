<?php

final class PhorgePackagesPublisherEditor
  extends PhorgePackagesEditor {

  public function getEditorObjectsDescription() {
    return pht('Package Publishers');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this publisher.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
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
      PhorgePackagesPublisherKeyTransaction::TRANSACTIONTYPE,
      pht('Duplicate'),
      pht(
        'The publisher key "%s" is already in use by another publisher.',
        $object->getPublisherKey()),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }

}
