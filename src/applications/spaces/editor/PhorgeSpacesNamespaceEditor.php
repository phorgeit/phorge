<?php

final class PhorgeSpacesNamespaceEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return pht('PhorgeSpacesApplication');
  }

  public function getEditorObjectsDescription() {
    return pht('Spaces');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this space.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created space %s.', $author, $object);
  }

}
