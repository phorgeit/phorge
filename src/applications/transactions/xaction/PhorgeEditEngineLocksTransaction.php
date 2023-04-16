<?php

final class PhorgeEditEngineLocksTransaction
  extends PhorgeEditEngineTransactionType {

  const TRANSACTIONTYPE = 'editengine.config.locks';

  public function generateOldValue($object) {
    return $object->getFieldLocks();
  }

  public function applyInternalEffects($object, $value) {
    $object->setFieldLocks($value);
  }

  public function getTitle() {
    return pht(
      '%s changed locked and hidden fields.',
      $this->renderAuthor());
  }

  public function hasChangeDetailView() {
    return true;
  }

  public function newChangeDetailView() {
    $viewer = $this->getViewer();

    return id(new PhorgeApplicationTransactionJSONDiffDetailView())
      ->setViewer($viewer)
      ->setOld($this->getOldValue())
      ->setNew($this->getNewValue());
  }

}
