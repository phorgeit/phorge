<?php

final class PhorgeRepositoryDescriptionTransaction
  extends PhorgeRepositoryTransactionType {

  const TRANSACTIONTYPE = 'repo:description';

  public function generateOldValue($object) {
    return $object->getDetail('description');
  }

  public function applyInternalEffects($object, $value) {
    $object->setDetail('description', $value);
  }

  public function getTitle() {
    return pht(
      '%s updated the description for this repository.',
      $this->renderAuthor());
  }

  public function hasChangeDetailView() {
    return true;
  }

  public function getMailDiffSectionHeader() {
    return pht('CHANGES TO REPOSITORY DESCRIPTION');
  }

  public function newChangeDetailView() {
    $viewer = $this->getViewer();

    return id(new PhorgeApplicationTransactionTextDiffDetailView())
      ->setViewer($viewer)
      ->setOldText($this->getOldValue())
      ->setNewText($this->getNewValue());
  }

  public function newRemarkupChanges() {
    $changes = array();

    $changes[] = $this->newRemarkupChange()
      ->setOldValue($this->getOldValue())
      ->setNewValue($this->getNewValue());

    return $changes;
  }

}
