<?php

final class PhorgeDashboardPanelTransactionEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Dashboard Panels');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDGE;

    return $types;
  }

  protected function supportsSearch() {
    return true;
  }

}
