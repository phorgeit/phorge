<?php

/**
 * Short-hand for writing transactions to edit string `Description` field.
 */
trait PhorgeDescriptionTransactionTrait {

  use PhorgeTransactionObjectNameTrait;

  public function generateOldValue($object) {
    return $object->getDescription();
  }

  public function applyInternalEffects($object, $value) {
    $object->setDescription($value);
  }

  public function getTitle() {
    return pht(
      '%s updated the description.',
      $this->renderAuthor());
  }

  public function getTitleForFeed() {
    return pht(
      '%s updated the description for %s %s.',
      $this->renderAuthor(),
      $this->renderObjectType(),
      $this->renderObject());
  }


  public function hasChangeDetailView() {
    return true;
  }

  public function getMailDiffSectionHeader() {
    $upper = strtoupper($this->renderObjectType());
    return pht('CHANGES TO %s DESCRIPTION', $upper);
  }

  public function newChangeDetailView() {
    $viewer = $this->getViewer();

    return id(new PhabricatorApplicationTransactionTextDiffDetailView())
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

  public function getIcon() {
    return 'fa-file-text-o';
  }

}
