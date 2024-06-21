<?php

final class PhabricatorEditEngineConfigurationEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return PhabricatorTransactionsApplication::class;
  }

  public function getEditorObjectsDescription() {
    return pht('Edit Configurations');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhabricatorTransactions::TYPE_VIEW_POLICY;

    return $types;
  }

}
