<?php

final class AlmanacNamespaceEditor
  extends AlmanacEditor {

  public function getEditorObjectsDescription() {
    return pht('Almanac Namespace');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this namespace.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  protected function supportsSearch() {
    return true;
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function didCatchDuplicateKeyException(
    PhorgeLiskDAO $object,
    array $xactions,
    Exception $ex) {

    $errors = array();

    $errors[] = new PhorgeApplicationTransactionValidationError(
      null,
      pht('Invalid'),
      pht(
        'Another namespace with this name already exists. Each namespace '.
        'must have a unique name.'),
      null);

    throw new PhorgeApplicationTransactionValidationException($errors);
  }

}
