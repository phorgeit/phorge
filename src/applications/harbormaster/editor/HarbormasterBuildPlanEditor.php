<?php

final class HarbormasterBuildPlanEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeHarbormasterApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Harbormaster Build Plans');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this build plan.', $author);
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

}
