<?php

final class PhorgeCountdownEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'countdown.countdown';

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineName() {
    return pht('Countdowns');
  }

  public function getSummaryHeader() {
    return pht('Edit Countdowns');
  }

  public function getSummaryText() {
    return pht('Creates and edits countdowns.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeCountdownApplication';
  }

  protected function newEditableObject() {
    return PhorgeCountdown::initializeNewCountdown(
      $this->getViewer());
  }

  protected function newObjectQuery() {
    return id(new PhorgeCountdownQuery());
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Countdown');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Countdown');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Countdown: %s', $object->getTitle());
  }

  protected function getObjectEditShortText($object) {
    return pht('Edit Countdown');
  }

  protected function getObjectCreateShortText() {
    return pht('Create Countdown');
  }

  protected function getObjectName() {
    return pht('Countdown');
  }

  protected function getCommentViewHeaderText($object) {
    return pht('Last Words');
  }

  protected function getCommentViewButtonText($object) {
    return pht('Contemplate Infinity');
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function buildCustomEditFields($object) {
    $epoch_value = $object->getEpoch();
    if ($epoch_value === null) {
      $epoch_value = PhorgeTime::getNow();
    }

    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setIsRequired(true)
        ->setTransactionType(
            PhorgeCountdownTitleTransaction::TRANSACTIONTYPE)
        ->setDescription(pht('The countdown name.'))
        ->setConduitDescription(pht('Rename the countdown.'))
        ->setConduitTypeDescription(pht('New countdown name.'))
        ->setValue($object->getTitle()),
      id(new PhorgeEpochEditField())
        ->setKey('epoch')
        ->setLabel(pht('End Date'))
        ->setTransactionType(
            PhorgeCountdownEpochTransaction::TRANSACTIONTYPE)
        ->setDescription(pht('Date when the countdown ends.'))
        ->setConduitDescription(pht('Change the end date of the countdown.'))
        ->setConduitTypeDescription(pht('New countdown end date.'))
        ->setValue($epoch_value),
      id(new PhorgeRemarkupEditField())
        ->setKey('description')
        ->setLabel(pht('Description'))
        ->setTransactionType(
            PhorgeCountdownDescriptionTransaction::TRANSACTIONTYPE)
        ->setDescription(pht('Description of the countdown.'))
        ->setConduitDescription(pht('Change the countdown description.'))
        ->setConduitTypeDescription(pht('New description.'))
        ->setValue($object->getDescription()),
    );
  }

}
