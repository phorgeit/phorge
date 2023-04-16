<?php

final class PhorgeDashboardPortalEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Portals');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this portal.', $author);
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

  protected function supportsSearch() {
    return true;
  }

}
