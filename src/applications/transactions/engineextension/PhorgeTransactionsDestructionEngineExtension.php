<?php

final class PhorgeTransactionsDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'transactions';

  public function getExtensionName() {
    return pht('Transactions');
  }

  public function canDestroyObject(
    PhorgeDestructionEngine $engine,
    $object) {
    return ($object instanceof PhorgeApplicationTransactionInterface);
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $template = $object->getApplicationTransactionTemplate();
    $xactions = $template->loadAllWhere(
      'objectPHID = %s',
      $object->getPHID());
    foreach ($xactions as $xaction) {
      $engine->destroyObject($xaction);
    }
  }

}
