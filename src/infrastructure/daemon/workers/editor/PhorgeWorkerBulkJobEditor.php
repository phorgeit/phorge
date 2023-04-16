<?php

final class PhorgeWorkerBulkJobEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeDaemonsApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Bulk Jobs');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeWorkerBulkJobTransaction::TYPE_STATUS;
    $types[] = PhorgeTransactions::TYPE_EDGE;

    return $types;
  }

  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeWorkerBulkJobTransaction::TYPE_STATUS:
        return $object->getStatus();
    }
  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeWorkerBulkJobTransaction::TYPE_STATUS:
        return $xaction->getNewValue();
    }
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $type = $xaction->getTransactionType();
    $new = $xaction->getNewValue();

    switch ($type) {
      case PhorgeWorkerBulkJobTransaction::TYPE_STATUS:
        $object->setStatus($xaction->getNewValue());
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $type = $xaction->getTransactionType();
    $new = $xaction->getNewValue();

    switch ($type) {
      case PhorgeWorkerBulkJobTransaction::TYPE_STATUS:
        switch ($new) {
          case PhorgeWorkerBulkJob::STATUS_WAITING:
            PhorgeWorker::scheduleTask(
              'PhorgeWorkerBulkJobCreateWorker',
              array(
                'jobID' => $object->getID(),
              ),
              array(
                'priority' => PhorgeWorker::PRIORITY_BULK,
              ));
            break;
        }
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }



}
