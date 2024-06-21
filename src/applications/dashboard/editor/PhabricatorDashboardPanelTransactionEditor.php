<?php

final class PhabricatorDashboardPanelTransactionEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return PhabricatorDashboardApplication::class;
  }

  public function getEditorObjectsDescription() {
    return pht('Dashboard Panels');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDIT_POLICY;
    $types[] = PhabricatorTransactions::TYPE_EDGE;

    return $types;
  }

  protected function supportsSearch() {
    return true;
  }

}
