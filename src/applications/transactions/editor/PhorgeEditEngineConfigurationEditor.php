<?php

final class PhorgeEditEngineConfigurationEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeTransactionsApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Edit Configurations');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();
    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;

    return $types;
  }

}
