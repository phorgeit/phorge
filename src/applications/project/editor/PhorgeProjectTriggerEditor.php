<?php

final class PhorgeProjectTriggerEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeProjectApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Triggers');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this trigger.', $author);
  }

  public function getCreateObjectTitleForFeed($author, $object) {
    return pht('%s created %s.', $author, $object);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function supportsSearch() {
    return true;
  }

}
